import "@fortawesome/fontawesome-free/css/all.css";
import "@/assets/base.sass";
import customColors from "@/assets/template/exports/colors.module.scss";
import "vuetify/styles";
import { createVuetify } from "vuetify";
import { aliases, fa } from "vuetify/iconsets/fa";

const customLightTheme = {
  dark: false,
  colors: {
    background: customColors.background,
    surface: customColors.surface,
    surfaceLighten: customColors.surfaceLighten,
    primary: customColors.primary,
    secondary: customColors.secondary,
    error: customColors.error,
    info: customColors.info,
    success: customColors.success,
    warning: customColors.warning,
    accent: customColors.accent,
    textDark: customColors.textDark,
  },
};

export default createVuetify({
  blueprint: {
    defaults: {
      VCombobox: {
        variant: "underlined",
      },
      VSelect: {
        variant: "underlined",
        color: "textDark",
        density: "compact",
      },
      VTextarea: {
        variant: "underlined",
        color: "textDark",
      },
      VTextField: {
        variant: "underlined",
        density: "compact",
        color: "textDark",
      },
      VTabs: {
        density: "compact",
      },
      VTable: {
        density: "compact",
      },
    },
  },
  icons: {
    defaultSet: "fa",
    aliases,
    sets: {
      fa,
    },
  },
  theme: {
    defaultTheme: "customLightTheme",
    themes: {
      customLightTheme,
    },
  },
});
