import ConditionalFieldTrigger from './ConditionalFieldTrigger';

class RadioFieldTrigger extends ConditionalFieldTrigger {
    _handle() {
        console.log('Handling RadioField change ...', this.conditionalFields);
    }
}

export { RadioFieldTrigger as default };
