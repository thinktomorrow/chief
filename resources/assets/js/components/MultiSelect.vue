<template>
    <div>
        <multiselect
                v-on:input="clearErrors"
                :options="values"
                v-model="value"

                :multiple="multiple"
                :hide-selected="multiple"
                :close-on-select="!multiple"

                :label="labelkey"
                :track-by="valuekey"

                :group-label="grouplabel"
                :group-values="groupvalues"

                placeholder="Maak een selectie"
                no-result="geen resultaat gevonden"
                deselect-label="╳"
                selected-label="✓"
                select-label="✓"
        >
        </multiselect>
        <select v-if="name" style="display:none;" :name="(multiple) ? name + '[]' : name" :multiple="multiple">
            <template v-if="Object.keys(valuesAsSelectedOptions).length">
                <option selected="selected" v-for="(label, index) in valuesAsSelectedOptions"
                        :value="index"
                        :key="index"
                        v-text="label"
                ></option>
            </template>
            <template v-else>
                <option selected="selected" value=""></option>
            </template>
        </select>
    </div>
</template>

<script>

    /** @ref https://vue-multiselect.js.org */
    import Multiselect from 'vue-multiselect';

    export default {
        components: { Multiselect },
        props: {
            options: {default: function(){ return []; }, type: Array},
            selected: {default: null},
            name: {default: '', type: String},
            multiple: {default: false, type: Boolean},

            /**
             * Object / Array behaviour
             * selected value will default to option.id, visible label to option.label
             * e.g. [{id: 1, label: "first"}, {id: 2, label: "second"}]
             */
            valuekey: { default: null, type: String },
            labelkey: { default: null, type: String },

            /** Grouped options */
            groupvalues: { default: null, type: String },
            grouplabel: { default: null, type: String },
        },
        data () {
            return {
                // List of available options
                values: this.isJson(this.options) ? JSON.parse(this.options) : this.options,

                // Active selected option
//                value: this.multiple ? [] : null
                value: [],
            }
        },
        computed: {
            valuesAsSelectedOptions(){

                if(this.value === null) return [];

                if( this.isPrimitive(this.value) ){
                    return {[this.value] : this.value};
                }

                return this.pluck(this.value, this.valuekey, this.labelkey);
            },
            realValues(){

                /**
                 * For grouped values the real values are nested in a deeper
                 * level so we will need to extract those values first
                 */
                if(this.groupvalues){
                    return this.flattenGroupedValues(this.values, this.groupvalues);
                }

                return this.values;
            },
        },
        created(){
            this.resetValue();

            /**
             * Only allow to reset value triggered by any parent components
             */
            Eventbus.$on('resetMultiSelectValue', (parentId) => {

                if(this.$parent._uid == parentId){
                    this.resetValue();
                };

            });
        },
        watch:{

            // When selected property has changed after component is mounted
            // We'll make sure we'll update the selected values. Note that
            // This will remove any current selected values.
            selected(newValue, oldValue){

                if(this.isObject(oldValue) && JSON.stringify(oldValue) === JSON.stringify(newValue)) return;
                if(!this.isObject(oldValue) && newValue == oldValue) return;

                this.value = this.defaultValue();
            },

            value(newValue, oldValue){

                if(newValue == oldValue) return;

                this.notifyChange();
            }

        },
        methods: {
            resetValue(){
                this.value = this.defaultValue();
            },
            clearErrors(){
                if(this.name) Eventbus.$emit('clearErrors', this.name)
            },
            notifyChange(){
                Eventbus.$emit('updated-select', this.name, Object.keys(this.valuesAsSelectedOptions), this.value);
            },
            defaultValue(){

                let selected = this.isJson(this.selected) ? JSON.parse(this.selected) : this.selected;

                // If value is a primitive, we'll need to fetch the corresponding value object|string first
                if(this.isPrimitive(selected)) {
                    selected = this.find(this.realValues, selected, this.valuekey);
                }

                // If selected value is a single object, we'll use this a the appropriate value
                if(this.isObject(selected) && !this.isArray(selected)) {
                    return this.multiple ? [selected] : selected;
                }

                //  If by now this is not an array, we consider this to be invalid and set the default value to an empty array
                if( ! this.isArray(selected) ) return this.multiple ? [] : null;

                // Multiple default selected values
                let result = [];
                for(let i=0; i<selected.length;i++){
                    if(this.isObject(selected[i])){
                        result.push(selected[i]);
                    }else{
                        const val = this.find(this.realValues, selected[i], this.valuekey);
                        if(val) result.push(val);
                    }
                }

                return result;
            },
            find(values, value, key){

                if(!values || ! this.isArray(values)) return null;

                for(let i=0;i<values.length;i++){

                    let val = values[i];

                    if(this.isPrimitive(val) && val == value){
                        return val;
                    }else if(this.isObject(val) && key && val.hasOwnProperty(key) && val[key] == value){
                        return val;
                    }
                }

                return null;
            },
            // Pluck specific value from objects by key
            pluck(values, key, value){

                if(!values || this.isPrimitive(values)){
                    return values;
                }

                if(!value) value = key;

                if(Array.isArray(values))
                {
                    let result = {};
                    values.forEach((val) => {

                        if(this.isPrimitive(val)){
                            result[val] = val;
                        }else{

                            if(!key) throw "[Multiselect::pluck()] A key parameter is required for the pluck method. None given. You can set a :valuekey on the component element.";

                            result[val[key]] = val[value];
                        }

                    });

                    return result;
                }

                if(!key) throw "[Multiselect::pluck()] A key parameter is required for the pluck method. None given. You can set a :valuekey on the component element.";

                // Object
                return { [values[key]] : values[value] };
            },
            // This assumes a group setup
            flattenGroupedValues(values, key){

                let result = [];

                values.forEach((val) => {

                    if(this.isPrimitive(val)){
                        result.push(val);
                    }else if(this.isArray(val[key])){
                        result = result.concat(val[key]);
                    }else{
                        result.push(val[key]);
                    }

                });

                return result;
            },
            isJson(value){
                try {
                    JSON.parse(value);
                } catch(e) {
                    return false;
                }
                return true;
            },
            isPrimitive(value)
            {
                return value !== null && !this.isObject(value) && ! this.isArray(value);
            },
            isObject(value)
            {
                return value !== null && typeof value === "object";
            },
            isArray(value)
            {
                return Array.isArray(value);
            },
            isEmpty(value) {
                if (value === 0) return false;
                if (Array.isArray(value) && value.length === 0) return true;
                return !value;
            },
        },
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style type="text/css">

    .multiselect__tag{
        background-color: hsla(125, 48%, 40%, 1);
    }

    .multiselect__tags input, .multiselect__tags input:focus{
        border: none;
    }

    input[type="text"].multiselect__input:focus {
        box-shadow: none;
    }

    input[type="text"].multiselect__input {
        padding: 1px 0 0 5px;
    }

</style>
