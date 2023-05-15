import FormEngineValidation from '@typo3/backend/form-engine-validation.js';

export class TelephoneEvaluation {
    static registerCustomEvaluation(name) {
        FormEngineValidation.registerCustomEvaluation(name, TelephoneEvaluation.applyTelephoneValidationPattern);
    }

    static applyTelephoneValidationPattern(value) {
        const items = TYPO3.settings.TtAddress.Evaluation.telephoneValidationPattern.split('/');
        // fetch RegExp modifier and remove it
        const modifier = items.pop();
        // remove first item
        items.shift();
        const expression = new RegExp(items.join('/'), modifier);
        return value.replace(expression, '');
    }
}
