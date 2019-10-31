import Translations from "./Translations/Messages.js";
import {APP_CONTEXT} from './AppContext/AppContext';

/**
 * Translator class
 *
 * @author  Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */
class Translator {
    /**
     * Constructor
     */
    constructor() {
        this.language = APP_CONTEXT.app.language;
    }

    /**
     * Get the translation for specified key
     *
     * @param  String    key    Translation key
     * @return String    [type]    Translation value
     */
    trans(key) {
        if (Translations[this.language] && Translations[this.language][key]) {
            return Translations[this.language][key];
        }

        return '';
    }
}

export default Translator;
