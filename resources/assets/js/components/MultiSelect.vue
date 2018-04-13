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
                value: null
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
            }
        },
        mounted(){
            this.value = this.defaultValue();
        },
        methods: {
            clearErrors(){
                if(this.name) Eventbus.$emit('clearErrors', this.name)
            },
            defaultValue(){

                let selected = this.isJson(this.selected) ? JSON.parse(this.selected) : this.selected;

                // Single default selected value
                if(this.isObject(selected) && !this.isArray(selected)) return selected;
                if(this.isPrimitive(selected)) return this.find(this.realValues, selected, this.valuekey);

                // Multiple default selected values
                if( ! this.isArray(selected) ) return null;

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
                return !this.isObject(value) && ! this.isArray(value);
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
                if (value === 0) return false
                if (Array.isArray(value) && value.length === 0) return true
                return !value;
            },
        },
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style type="text/css">

    .multiselect__tags input, .multiselect__tags input:focus{
        border: none;
    }

    input[type="text"].multiselect__input {
        padding: 1px 0 0 5px;
    }

</style>


