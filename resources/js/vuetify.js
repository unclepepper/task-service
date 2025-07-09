import "vuetify/styles";
import {createVuetify} from "vuetify";
import * as components from  "vuetify/components"
import * as directives from  "vuetify/directives";

const devnightly = {
    colors: {
        primary: "#673AB7",
        secondary: "#424242",
        accent: "#82B1FF",

    }
}
const vuetify = createVuetify({
    components,
    directives,
    theme: {
        defaultTheme: "devnightly",
        themes: {
            devnightly
        },
    },
});
export default vuetify;
