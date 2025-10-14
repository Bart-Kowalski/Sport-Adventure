<?php
namespace Advanced_Themer_Bricks;
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly


class AT__Framework {
    static public $at_framework = [
        "values" => [
            "variables_cat" => '
                [
                    {
                        "id": "at_site",
                        "name": "Site",
                        "at_framework": true,
                        "at_version": "1.0.0"
                    },
                    {
                        "id": "at_grids",
                        "name": "Grids",
                        "cssCategory": "_grid",
                        "at_framework": true,
                        "at_version": "1.0.0"
                    },
                    {
                        "id": "at_spacing",
                        "name": "Spacing",
                        "cssCategory": "_spacing",
                        "at_framework": true,
                        "at_version": "1.0.0"
                    },
                    {
                        "id": "at_gap",
                        "name": "Gap",
                        "cssCategory": "_gap",
                        "description": "The container-gap is mapped under <strong>Theme Styles > Element – Section</strong>, while the content-gap is used in both the default <strong>Container and Block</strong> settings.",
                        "at_framework": true,
                        "at_version": "1.0.0"
                    },
                    {
                        "id": "at_typography",
                        "name": "Typography",
                        "cssCategory": "_typography",
                        "description": "The following variables control heading and text sizes across your entire website, and are mapped under <strong>Theme Styles > Typography</strong>.",
                        "at_framework": true,
                        "at_version": "1.0.0"
                    },
                    {
                        "id": "at_border",
                        "name": "Border",
                        "cssCategory": "_border",
                        "at_framework": true,
                        "at_version": "1.0.0"
                    },
                    {
                        "id": "at_box-shadow",
                        "name": "Box Shadow",
                        "description": "The following values can be used within any Box-Shadow modal by navigating to <strong>Color > Raw</strong>.",
                        "cssCategory": "_border",
                        "at_framework": true,
                        "at_version": "1.0.0"
                    },
                    {
                        "id": "at_btn",
                        "name": "Button",
                        "at_framework": true,
                        "description": "The following variables are shared across all button variants and are mapped under <strong>Theme Styles > Elements - Button > Style - Default</strong>.",
                        "at_version": "1.0.0"
                    },
                    {
                        "id": "at_btn-primary",
                        "name": "Button - Primary",
                        "at_framework": true,
                        "description": "The following variables define the primary button’s colors and are mapped under <strong>Theme Styles > Elements - Button > Style - Primary</strong>.",
                        "at_version": "1.0.0"
                    },
                    {
                        "id": "at_btn-secondary",
                        "name": "Button - Secondary",
                        "description": "The following variables define the secondary button’s colors and are mapped under <strong>Theme Styles > Elements - Button > Style - Secondary</strong>.",
                        "at_framework": true,
                        "at_version": "1.0.0"
                    },
                    {
                        "id": "at_btn-light",
                        "name": "Button - Light",
                        "description": "The following variables define the light button’s colors and are mapped under <strong>Theme Styles > Elements - Button > Style - Light</strong>.",
                        "at_framework": true,
                        "at_version": "1.0.0"
                    },
                    {
                        "id": "at_btn-dark",
                        "name": "Button - Dark",
                        "description": "The following variables define the dark button’s colors and are mapped under <strong>Theme Styles > Elements - Button > Style - Dark</strong>.",
                        "at_framework": true,
                        "at_version": "1.0.0"
                    },
                    {
                        "id": "at_btn-size-small",
                        "name": "Button - Sizes",
                        "description": "The following variables define the small button’s sizes and are mapped under <strong>Theme Styles > Elements - Button > Sizes</strong>.",
                        "at_framework": true,
                        "at_version": "1.0.0"
                    },
                    {
                        "id": "at_forms",
                        "name": "Forms",
                        "at_framework": true,
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "at_links",
                        "name": "Links",
                        "description": "The following variables define your link’s colors and are mapped under <strong>Theme Styles > Links > Typography</strong>.",
                        "at_framework": true,
                        "at_version": "1.0.0"
                    },
                    {
                        "id": "at_marks",
                        "name": "Marks",
                        "at_framework": true,
                        "at_version": "1.0.0"
                    },
                    {
                        "id": "at_filter",
                        "name": "Filters / Transitions",
                        "cssCategory": "_filter",
                        "at_framework": true,
                        "at_version": "1.0.0"
                    },
                    {
                        "id": "at_entrance-animations",
                        "name": "Entrance Animations",
                        "at_framework": true,
                        "at_version": "1.2.0"
                    },
                    {
                        "id": "at_a11y",
                        "name": "A11y",
                        "description": "The focus-color and focus-width are mapped in <strong>Theme Styles > Typography > Focus Outline</strong>, while the focus-offset is used in the <strong>global file in Advanced CSS</strong>.",
                        "at_framework": true,
                        "at_version": "1.0.0"
                    },
                    {
                        "id": "at_svg",
                        "name": "SVG",
                        "at_framework": true,
                        "at_version": "1.0.0"
                    }
                ]',
            "variables" => '
                [
                    {
                        "id": "at_site-box-width",
                        "name": "at-site-box-max-width",
                        "type": "static",
                        "category": "at_site",
                        "value": "1300px",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Site Box Max-width",
                        "group": "ggruzc",
                        "groupName": "Global Width",
                        "groupBase": true
                    },
                    {
                        "id": "at_section-max-width",
                        "name": "at-section-max-width",
                        "type": "static",
                        "category": "at_site",
                        "value": "100%",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Section Max-width",
                        "group": "ggruzc"
                    },
                    {
                        "id": "at_blog-width",
                        "name": "at-blog-width",
                        "type": "static",
                        "category": "at_site",
                        "value": "900px",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Blog Width",
                        "group": "ggruzc"
                    },
                    {
                        "id": "at_section--alt-background",
                        "name": "at-section--alt-background",
                        "type": "color",
                        "category": "at_site",
                        "value": "var(--at-neutral-t-6)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Section Alt Background",
                        "group": "rdptei",
                        "groupName": "Alternate Backgrounds",
                        "groupBase": true
                    },
                    {
                        "id": "at_header-height",
                        "name": "at-header-height",
                        "type": "static",
                        "category": "at_site",
                        "value": "0px",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Header Height",
                        "group": "tfmevh",
                        "groupName": "Header Height",
                        "groupBase": true
                    },
                    {
                        "id": "at_grid--1",
                        "name": "at-grid--1",
                        "type": "static",
                        "category": "at_grids",
                        "value": "minmax(0,1fr)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "1",
                        "group": "noqgjy",
                        "groupName": "Grid Columns",
                        "groupBase": true
                    },
                    {
                        "id": "at_grid--2",
                        "name": "at-grid--2",
                        "type": "static",
                        "category": "at_grids",
                        "value": "repeat( 2, minmax(0,1fr) )",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "2",
                        "group": "noqgjy"
                    },
                    {
                        "id": "at_grid--3",
                        "name": "at-grid--3",
                        "type": "static",
                        "category": "at_grids",
                        "value": "repeat( 3, minmax(0,1fr) )",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "3",
                        "group": "noqgjy"
                    },
                    {
                        "id": "at_grid--4",
                        "name": "at-grid--4",
                        "type": "static",
                        "category": "at_grids",
                        "value": "repeat( 4, minmax(0,1fr) )",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "4",
                        "group": "noqgjy"
                    },
                    {
                        "id": "at_grid--5",
                        "name": "at-grid--5",
                        "type": "static",
                        "category": "at_grids",
                        "value": "repeat( 5, minmax(0,1fr) )",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "5",
                        "group": "noqgjy"
                    },
                    {
                        "id": "at_grid--6",
                        "name": "at-grid--6",
                        "type": "static",
                        "category": "at_grids",
                        "value": "repeat( 6, minmax(0,1fr) )",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "6",
                        "group": "noqgjy"
                    },
                    {
                        "id": "at_grid--7",
                        "name": "at-grid--7",
                        "type": "static",
                        "category": "at_grids",
                        "value": "repeat( 7, minmax(0,1fr) )",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "7",
                        "group": "noqgjy"
                    },
                    {
                        "id": "at_grid--8",
                        "name": "at-grid--8",
                        "type": "static",
                        "category": "at_grids",
                        "value": "repeat( 8, minmax(0,1fr) )",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "8",
                        "group": "noqgjy"
                    },
                    {
                        "id": "at_grid--9",
                        "name": "at-grid--9",
                        "type": "static",
                        "category": "at_grids",
                        "value": "repeat( 9, minmax(0,1fr) )",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "9",
                        "group": "noqgjy"
                    },
                    {
                        "id": "at_grid--10",
                        "name": "at-grid--10",
                        "type": "static",
                        "category": "at_grids",
                        "value": "repeat( 10, minmax(0,1fr) )",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "10",
                        "group": "noqgjy"
                    },
                    {
                        "id": "at_grid--11",
                        "name": "at-grid--11",
                        "type": "static",
                        "category": "at_grids",
                        "value": "repeat( 11, minmax(0,1fr) )",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "11",
                        "group": "noqgjy"
                    },
                    {
                        "id": "at_grid--12",
                        "name": "at-grid--12",
                        "type": "static",
                        "category": "at_grids",
                        "value": "repeat( 12, minmax(0,1fr) )",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "12",
                        "group": "noqgjy"
                    },
                    {
                        "id": "at_grid--1-2",
                        "name": "at-grid--1-2",
                        "type": "static",
                        "category": "at_grids",
                        "value": "minmax(0,1fr)  minmax(0,2fr) ",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "1-2",
                        "group": "jzxerm",
                        "groupName": "Special Grids",
                        "groupBase": true
                    },
                    {
                        "id": "at_grid--2-1",
                        "name": "at-grid--2-1",
                        "type": "static",
                        "category": "at_grids",
                        "value": "minmax(0,2fr) minmax(0,1fr) ",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "2-1",
                        "group": "jzxerm"
                    },
                    {
                        "id": "at_grid--1-3",
                        "name": "at-grid--1-3",
                        "type": "static",
                        "category": "at_grids",
                        "value": "minmax(0,1fr)  minmax(0,3fr) ",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "1-3",
                        "group": "jzxerm"
                    },
                    {
                        "id": "at_grid--3-1",
                        "name": "at-grid--3-1",
                        "type": "static",
                        "category": "at_grids",
                        "value": "minmax(0,3fr) minmax(0,1fr) ",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "3-1",
                        "group": "jzxerm"
                    },
                    {
                        "id": "at_grid-auto-fit",
                        "name": "at-grid-auto-fit",
                        "type": "static",
                        "category": "at_grids",
                        "value": "repeat( auto-fit, minmax( min( var(--at-grid-auto-col-min-width), 100%), 1fr) )",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Auto-fit",
                        "group": "nzdgrn",
                        "groupName": "Auto Grids",
                        "groupBase": true
                    },
                    {
                        "id": "at_grid-auto-fill",
                        "name": "at-grid-auto-fill",
                        "type": "static",
                        "category": "at_grids",
                        "value": "repeat( auto-fill, minmax( min( var(--at-grid-auto-col-min-width), 100%), 1fr) )",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Auto-fill",
                        "group": "nzdgrn"
                    },
                    {
                        "id": "at_grid-auto-col-min-width",
                        "name": "at-grid-auto-col-min-width",
                        "type": "static",
                        "category": "at_grids",
                        "value": "270px",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Col Min Width",
                        "group": "nzdgrn"
                    },
                    {
                        "id": "at_space--3xs",
                        "scaleId": "pgcdtw",
                        "suffix": "3xs",
                        "name": "at-space--3xs",
                        "step": -3,
                        "multiplier": 0.296,
                        "value": "calc(var(--at-space--s) * 0.296)",
                        "type": "scale",
                        "category": "at_spacing",
                        "at_framework": true,
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "at_space--2xs",
                        "scaleId": "pgcdtw",
                        "suffix": "2xs",
                        "name": "at-space--2xs",
                        "step": -2,
                        "multiplier": 0.444,
                        "value": "calc(var(--at-space--s) * 0.444)",
                        "type": "scale",
                        "category": "at_spacing",
                        "at_framework": true,
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "at_space--xs",
                        "scaleId": "pgcdtw",
                        "suffix": "xs",
                        "name": "at-space--xs",
                        "step": -1,
                        "multiplier": 0.667,
                        "value": "calc(var(--at-space--s) * 0.667)",
                        "type": "scale",
                        "category": "at_spacing",
                        "at_framework": true,
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "at_space--s",
                        "scaleId": "pgcdtw",
                        "label": "at-space-",
                        "suffix": "s",
                        "name": "at-space--s",
                        "customLabel": "Spacing",
                        "step": 0,
                        "smallSteps": 3,
                        "largeSteps": 6,
                        "multiplier": 1,
                        "type": "scale",
                        "scaleType": 1.5,
                        "value": "clamp(calc(1rem * (10 / var(--base-font))), calc(1rem * ((((-1 * var(--min-viewport)) / var(--base-font)) * ((20 - 10) / var(--base-font)) / ((var(--max-viewport) - var(--min-viewport)) / var(--base-font))) + (10 / var(--base-font)))) + (((20 - 10) / var(--base-font)) / ((var(--max-viewport) - var(--min-viewport)) / var(--base-font)) * 100) * var(--clamp-unit), calc(1rem * (20 / var(--base-font))));",
                        "base": true,
                        "min": "10",
                        "max": "20",
                        "category": "at_spacing",
                        "at_framework": true,
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "at_space--m",
                        "scaleId": "pgcdtw",
                        "suffix": "m",
                        "name": "at-space--m",
                        "step": 1,
                        "multiplier": 1.5,
                        "value": "calc(var(--at-space--s) * 1.5)",
                        "type": "scale",
                        "category": "at_spacing",
                        "at_framework": true,
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "at_space--l",
                        "scaleId": "pgcdtw",
                        "suffix": "l",
                        "name": "at-space--l",
                        "step": 2,
                        "multiplier": 2.25,
                        "value": "calc(var(--at-space--s) * 2.25)",
                        "type": "scale",
                        "category": "at_spacing",
                        "at_framework": true,
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "at_space--xl",
                        "scaleId": "pgcdtw",
                        "suffix": "xl",
                        "name": "at-space--xl",
                        "step": 3,
                        "multiplier": 3.375,
                        "value": "calc(var(--at-space--s) * 3.375)",
                        "type": "scale",
                        "category": "at_spacing",
                        "at_framework": true,
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "at_space--2xl",
                        "scaleId": "pgcdtw",
                        "suffix": "2xl",
                        "name": "at-space--2xl",
                        "step": 4,
                        "multiplier": 5.063,
                        "value": "calc(var(--at-space--s) * 5.063)",
                        "type": "scale",
                        "category": "at_spacing",
                        "at_framework": true,
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "at_space--3xl",
                        "scaleId": "pgcdtw",
                        "suffix": "3xl",
                        "name": "at-space--3xl",
                        "step": 5,
                        "multiplier": 7.594,
                        "value": "calc(var(--at-space--s) * 7.594)",
                        "type": "scale",
                        "category": "at_spacing",
                        "at_framework": true,
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "at_section-padding-block",
                        "name": "at-section-padding-block",
                        "type": "static",
                        "category": "at_spacing",
                        "value": "var(--at-space--2xl)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Padding Block",
                        "group": "yslrqm",
                        "groupName": "Section Spacing",
                        "groupBase": true
                    },
                    {
                        "id": "at_gutter",
                        "name": "at-gutter",
                        "type": "static",
                        "category": "at_spacing",
                        "value": "var(--at-space--s)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Gutter",
                        "group": "yslrqm"
                    },
                    {
                        "id": "at_block-overlap",
                        "name": "at-block-overlap",
                        "type": "static",
                        "category": "at_spacing",
                        "value": "var(--at-section-padding-block)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Block Overlap",
                        "group": "rgeyia",
                        "groupName": "Block Spacing",
                        "groupBase": true
                    },
                    {
                        "id": "at_container-gap",
                        "name": "at-container-gap",
                        "type": "static",
                        "category": "at_gap",
                        "value": "var(--at-space--l)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Default",
                        "group": "efdvqf",
                        "groupName": "Container Gap",
                        "groupBase": true
                    },
                    {
                        "id": "at_gap-gap--xs",
                        "name": "at-content-gap--xs",
                        "type": "static",
                        "category": "at_gap",
                        "value": "var(--at-space--2xs)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "xs",
                        "group": "iprtcf"
                    },
                    {
                        "id": "at_gap-gap--s",
                        "name": "at-content-gap--s",
                        "type": "static",
                        "category": "at_gap",
                        "value": "var(--at-space--xs)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "s",
                        "group": "iprtcf"
                    },
                    {
                        "id": "at_gap-gap",
                        "name": "at-content-gap",
                        "type": "static",
                        "category": "at_gap",
                        "value": "var(--at-space--s)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Default",
                        "group": "iprtcf",
                        "groupName": "Content Gap",
                        "groupBase": true
                    },
                    {
                        "id": "at_grid-gap--s",
                        "name": "at-grid-gap--s",
                        "type": "static",
                        "category": "at_gap",
                        "value": "var(--at-space--xs)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "s",
                        "group": "zqkxzt"
                    },
                    {
                        "id": "at_grid-gap",
                        "name": "at-grid-gap",
                        "type": "static",
                        "category": "at_gap",
                        "value": "var(--at-space--s)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Default",
                        "group": "zqkxzt",
                        "groupName": "Grid Gap",
                        "groupBase": true
                    },
                    {
                        "id": "at_grid-gap--l",
                        "name": "at-grid-gap--l",
                        "type": "static",
                        "category": "at_gap",
                        "value": "var(--at-space--m)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "l",
                        "group": "zqkxzt"
                    },
                    {
                        "id": "at_heading--2xs",
                        "scaleId": "wwotsj",
                        "suffix": "2xs",
                        "name": "at-heading--2xs",
                        "step": -2,
                        "multiplier": 0.64,
                        "value": "calc(var(--at-heading--s) * 0.64)",
                        "type": "scale",
                        "category": "at_typography",
                        "at_framework": true,
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "at_heading--xs",
                        "scaleId": "wwotsj",
                        "suffix": "xs",
                        "name": "at-heading--xs",
                        "step": -1,
                        "multiplier": 0.8,
                        "value": "calc(var(--at-heading--s) * 0.8)",
                        "type": "scale",
                        "category": "at_typography",
                        "at_framework": true,
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "at_heading--s",
                        "scaleId": "wwotsj",
                        "label": "at-heading-",
                        "suffix": "s",
                        "name": "at-heading--s",
                        "customLabel": "Heading",
                        "step": 0,
                        "smallSteps": "2",
                        "largeSteps": "4",
                        "multiplier": 1,
                        "type": "scale",
                        "scaleType": 1.25,
                        "value": "clamp(calc(1rem * (18 / var(--base-font))), calc(1rem * ((((-1 * var(--min-viewport)) / var(--base-font)) * ((24 - 18) / var(--base-font)) / ((var(--max-viewport) - var(--min-viewport)) / var(--base-font))) + (18 / var(--base-font)))) + (((24 - 18) / var(--base-font)) / ((var(--max-viewport) - var(--min-viewport)) / var(--base-font)) * 100) * var(--clamp-unit), calc(1rem * (24 / var(--base-font))));",
                        "base": true,
                        "min": 18,
                        "max": "24",
                        "category": "at_typography",
                        "preview": "typography",
                        "at_framework": true,
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "at_heading--m",
                        "scaleId": "wwotsj",
                        "suffix": "m",
                        "name": "at-heading--m",
                        "step": 1,
                        "multiplier": 1.25,
                        "value": "calc(var(--at-heading--s) * 1.25)",
                        "type": "scale",
                        "category": "at_typography",
                        "at_framework": true,
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "at_heading--l",
                        "scaleId": "wwotsj",
                        "suffix": "l",
                        "name": "at-heading--l",
                        "step": 2,
                        "multiplier": 1.563,
                        "value": "calc(var(--at-heading--s) * 1.563)",
                        "type": "scale",
                        "category": "at_typography",
                        "at_framework": true,
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "at_heading--xl",
                        "scaleId": "wwotsj",
                        "suffix": "xl",
                        "name": "at-heading--xl",
                        "step": 3,
                        "multiplier": 1.953,
                        "value": "calc(var(--at-heading--s) * 1.953)",
                        "type": "scale",
                        "category": "at_typography",
                        "at_framework": true,
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "at_text--2xs",
                        "scaleId": "jnoezm",
                        "suffix": "2xs",
                        "name": "at-text--2xs",
                        "step": -2,
                        "multiplier": 0.64,
                        "value": "calc(var(--at-text--s) * 0.64)",
                        "type": "scale",
                        "category": "at_typography",
                        "at_framework": true,
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "at_text--xs",
                        "scaleId": "jnoezm",
                        "suffix": "xs",
                        "name": "at-text--xs",
                        "step": -1,
                        "multiplier": 0.8,
                        "value": "calc(var(--at-text--s) * 0.8)",
                        "type": "scale",
                        "category": "at_typography",
                        "at_framework": true,
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "at_text--s",
                        "scaleId": "jnoezm",
                        "label": "at-text-",
                        "suffix": "s",
                        "name": "at-text--s",
                        "customLabel": "Text",
                        "step": 0,
                        "smallSteps": "2",
                        "largeSteps": 6,
                        "multiplier": 1,
                        "type": "scale",
                        "scaleType": 1.25,
                        "value": "clamp(calc(1rem * (15 / var(--base-font))), calc(1rem * ((((-1 * var(--min-viewport)) / var(--base-font)) * ((18 - 15) / var(--base-font)) / ((var(--max-viewport) - var(--min-viewport)) / var(--base-font))) + (15 / var(--base-font)))) + (((18 - 15) / var(--base-font)) / ((var(--max-viewport) - var(--min-viewport)) / var(--base-font)) * 100) * var(--clamp-unit), calc(1rem * (18 / var(--base-font))));",
                        "base": true,
                        "min": "15",
                        "max": "18",
                        "category": "at_typography",
                        "preview": "typography",
                        "at_framework": true,
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "at_text--m",
                        "scaleId": "jnoezm",
                        "suffix": "m",
                        "name": "at-text--m",
                        "step": 1,
                        "multiplier": 1.25,
                        "value": "calc(var(--at-text--s) * 1.25)",
                        "type": "scale",
                        "category": "at_typography",
                        "at_framework": true,
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "at_text--l",
                        "scaleId": "jnoezm",
                        "suffix": "l",
                        "name": "at-text--l",
                        "step": 2,
                        "multiplier": 1.563,
                        "value": "calc(var(--at-text--s) * 1.563)",
                        "type": "scale",
                        "category": "at_typography",
                        "at_framework": true,
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "at_text--xl",
                        "scaleId": "jnoezm",
                        "suffix": "xl",
                        "name": "at-text--xl",
                        "step": 3,
                        "multiplier": 1.953,
                        "value": "calc(var(--at-text--s) * 1.953)",
                        "type": "scale",
                        "category": "at_typography",
                        "at_framework": true,
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "at_text--2xl",
                        "scaleId": "jnoezm",
                        "suffix": "2xl",
                        "name": "at-text--2xl",
                        "step": 4,
                        "multiplier": 2.441,
                        "value": "calc(var(--at-text--s) * 2.441)",
                        "type": "scale",
                        "category": "at_typography",
                        "at_framework": true,
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "at_text--3xl",
                        "scaleId": "jnoezm",
                        "suffix": "3xl",
                        "name": "at-text--3xl",
                        "step": 5,
                        "multiplier": 3.052,
                        "value": "calc(var(--at-text--s) * 3.052)",
                        "type": "scale",
                        "category": "at_typography",
                        "at_framework": true,
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "at_radius--2xs",
                        "scaleId": "gzlwpp",
                        "suffix": "2xs",
                        "name": "at-radius--2xs",
                        "step": -2,
                        "multiplier": 0.382,
                        "value": "calc(var(--at-radius--s) * 0.382)",
                        "type": "scale",
                        "category": "at_border",
                        "at_framework": true,
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "at_radius--xs",
                        "scaleId": "gzlwpp",
                        "suffix": "xs",
                        "name": "at-radius--xs",
                        "step": -1,
                        "multiplier": 0.618,
                        "value": "calc(var(--at-radius--s) * 0.618)",
                        "type": "scale",
                        "category": "at_border",
                        "at_framework": true,
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "at_radius--s",
                        "scaleId": "gzlwpp",
                        "label": "at-radius-",
                        "suffix": "s",
                        "name": "at-radius--s",
                        "customLabel": "Border-radius",
                        "step": 0,
                        "smallSteps": "2",
                        "largeSteps": "4",
                        "multiplier": 1,
                        "type": "scale",
                        "scaleType": 1.618,
                        "value": "clamp(calc(1rem * (5 / var(--base-font))), calc(1rem * ((((-1 * var(--min-viewport)) / var(--base-font)) * ((10 - 5) / var(--base-font)) / ((var(--max-viewport) - var(--min-viewport)) / var(--base-font))) + (5 / var(--base-font)))) + (((10 - 5) / var(--base-font)) / ((var(--max-viewport) - var(--min-viewport)) / var(--base-font)) * 100) * var(--clamp-unit), calc(1rem * (10 / var(--base-font))));",
                        "base": true,
                        "min": "5",
                        "max": "10",
                        "category": "at_border",
                        "preview": "borderRadius",
                        "at_framework": true,
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "at_radius--m",
                        "scaleId": "gzlwpp",
                        "suffix": "m",
                        "name": "at-radius--m",
                        "step": 1,
                        "multiplier": 1.618,
                        "value": "calc(var(--at-radius--s) * 1.618)",
                        "type": "scale",
                        "category": "at_border",
                        "at_framework": true,
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "at_radius--l",
                        "scaleId": "gzlwpp",
                        "suffix": "l",
                        "step": 2,
                        "multiplier": 2.618,
                        "type": "scale",
                        "name": "at-radius--l",
                        "value": "calc(var(--at-radius--s) * 2.618)",
                        "category": "at_border",
                        "at_framework": true,
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "at_radius--xl",
                        "scaleId": "gzlwpp",
                        "suffix": "xl",
                        "step": 3,
                        "multiplier": 4.236,
                        "type": "scale",
                        "name": "at-radius--xl",
                        "value": "calc(var(--at-radius--s) * 4.236)",
                        "category": "at_border",
                        "at_framework": true,
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "at_border-width",
                        "name": "at-border-width",
                        "type": "static",
                        "category": "at_border",
                        "value": "1px",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border-width",
                        "group": "rjgkyf",
                        "groupName": "Border Styles",
                        "groupBase": true
                    },
                    {
                        "id": "at_border-type",
                        "name": "at-border-type",
                        "type": "static",
                        "category": "at_border",
                        "value": "solid",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border-type",
                        "group": "rjgkyf"
                    },
                    {
                        "id": "at_border-color",
                        "name": "at-border-color",
                        "type": "color",
                        "category": "at_border",
                        "value": "var(--at-black-t-5)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border-color",
                        "group": "rjgkyf"
                    },
                    {
                        "id": "at_border--standard",
                        "name": "at-border--standard",
                        "type": "static",
                        "category": "at_border",
                        "value": "var(--at-border-width) var(--at-border-type) var(--at-border-color)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border Standard",
                        "group": "rjgkyf"
                    },
                    {
                        "id": "at_radius-full",
                        "name": "at-radius-full",
                        "type": "static",
                        "category": "at_border",
                        "value": "999rem",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Radius Full"
                    },
                    {
                        "id": "at_shadow--s",
                        "name": "at-shadow--s",
                        "type": "static",
                        "category": "at_box-shadow",
                        "value": "rgba(0, 0, 0, 0.1) 0px 1px 3px 0px, rgba(0, 0, 0, 0.06) 0px 1px 2px 0px",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "s",
                        "group": "nytglr",
                        "groupName": "Box Shadow",
                        "groupBase": true
                    },
                    {
                        "id": "at_shadow--m",
                        "name": "at-shadow--m",
                        "type": "static",
                        "category": "at_box-shadow",
                        "value": "rgba(0, 0, 0, 0.1) 0px 4px 6px -1px, rgba(0, 0, 0, 0.06) 0px 2px 4px -1px",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "m",
                        "group": "nytglr"
                    },
                    {
                        "id": "at_shadow--l",
                        "name": "at-shadow--l",
                        "type": "static",
                        "category": "at_box-shadow",
                        "value": "rgba(0, 0, 0, 0.1) 0px 10px 15px -3px, rgba(0, 0, 0, 0.05) 0px 4px 6px -2px",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "l",
                        "group": "nytglr"
                    },
                    {
                        "id": "at_shadow--xl",
                        "name": "at-shadow--xl",
                        "type": "static",
                        "category": "at_box-shadow",
                        "value": "rgba(0, 0, 0, 0.1) 0px 20px 25px -5px, rgba(0, 0, 0, 0.04) 0px 10px 10px -5px",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "xl",
                        "group": "nytglr"
                    },
                    {
                        "id": "at_shadow--2xl",
                        "name": "at-shadow--2xl",
                        "type": "static",
                        "category": "at_box-shadow",
                        "value": "rgba(0, 0, 0, 0.25) 0px 25px 50px -12px",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "2xl",
                        "group": "nytglr"
                    },
                    {
                        "id": "at_btn-border-radius",
                        "name": "at-btn-border-radius",
                        "type": "static",
                        "category": "at_btn",
                        "value": "var(--at-radius--s)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border-radius",
                        "group": "gzcazk",
                        "groupName": "Default",
                        "groupBase": true
                    },
                    {
                        "id": "at_btn-border-width",
                        "name": "at-btn-border-width",
                        "type": "static",
                        "category": "at_btn",
                        "value": "2px",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border-width",
                        "group": "gzcazk"
                    },
                    {
                        "id": "at_btn-shadow",
                        "name": "at-btn-shadow",
                        "type": "static",
                        "category": "at_btn",
                        "value": "none",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Shadow",
                        "group": "gzcazk"
                    },
                    {
                        "id": "at_btn-transition",
                        "name": "at-btn-transition",
                        "type": "static",
                        "category": "at_btn",
                        "value": "all var(--at-duration--fast) ease",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Transition",
                        "group": "gzcazk"
                    },
                    {
                        "id": "at_btn-border-radius--hover",
                        "name": "at-btn-border-radius--hover",
                        "type": "static",
                        "category": "at_btn",
                        "value": "var(--at-radius--s)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border-radius",
                        "group": "ozwcsn",
                        "groupName": "Default - Hover",
                        "groupBase": true
                    },
                    {
                        "id": "at_btn-border-width--hover",
                        "name": "at-btn-border-width--hover",
                        "type": "static",
                        "category": "at_btn",
                        "value": "2px",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border-width",
                        "group": "ozwcsn"
                    },
                    {
                        "id": "at_btn-shadow--hover",
                        "name": "at-btn-shadow--hover",
                        "type": "static",
                        "category": "at_btn",
                        "value": "var(--at-shadow--l)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Shadow",
                        "group": "ozwcsn"
                    },
                    {
                        "id": "at_btn-outline-border-radius",
                        "name": "at-btn-outline-border-radius",
                        "type": "static",
                        "category": "at_btn",
                        "value": "var(--at-radius--s)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border-radius",
                        "group": "xsxazr",
                        "groupName": "Outline",
                        "groupBase": true
                    },
                    {
                        "id": "at_btn-outline-border-width",
                        "name": "at-btn-outline-border-width",
                        "type": "static",
                        "category": "at_btn",
                        "value": "2px",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border-width",
                        "group": "xsxazr"
                    },
                    {
                        "id": "at_btn-outline-shadow",
                        "name": "at-btn-outline-shadow",
                        "type": "static",
                        "category": "at_btn",
                        "value": "none",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Shadow",
                        "group": "xsxazr"
                    },
                    {
                        "id": "at_btn-outline-border-radius--hover",
                        "name": "at-btn-outline-border-radius--hover",
                        "type": "static",
                        "category": "at_btn",
                        "value": "var(--at-radius--s)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border-radius",
                        "group": "jjyiyh",
                        "groupName": "Outline - Hover",
                        "groupBase": true
                    },
                    {
                        "id": "at_btn-outline-border-width--hover",
                        "name": "at-btn-outline-border-width--hover",
                        "type": "static",
                        "category": "at_btn",
                        "value": "2px",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border-width",
                        "group": "jjyiyh"
                    },
                    {
                        "id": "at_btn-outline-shadow--hover",
                        "name": "at-btn-outline-shadow--hover",
                        "type": "static",
                        "category": "at_btn",
                        "value": "var(--at-shadow--l)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Shadow",
                        "group": "jjyiyh"
                    },
                    {
                        "id": "at_btn-primary-color",
                        "name": "at-btn-primary-color",
                        "type": "color",
                        "category": "at_btn-primary",
                        "value": "var(--at-primary-l-6)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Color",
                        "group": "ybluto",
                        "groupName": "Primary",
                        "groupBase": true
                    },
                    {
                        "id": "at_btn-primary-background",
                        "name": "at-btn-primary-background",
                        "type": "color",
                        "category": "at_btn-primary",
                        "value": "var(--at-primary)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Background-color",
                        "group": "ybluto"
                    },
                    {
                        "id": "at_btn-primary-border-color",
                        "name": "at-btn-primary-border-color",
                        "type": "color",
                        "category": "at_btn-primary",
                        "value": "var(--at-primary)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border-color",
                        "group": "ybluto"
                    },
                    {
                        "id": "at_btn-primary-color--hover",
                        "name": "at-btn-primary-color--hover",
                        "type": "color",
                        "category": "at_btn-primary",
                        "value": "var(--at-primary-l-6)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Color",
                        "group": "ijyfyo",
                        "groupName": "Primary - Hover",
                        "groupBase": true
                    },
                    {
                        "id": "at_btn-primary-background--hover",
                        "name": "at-btn-primary-background--hover",
                        "type": "color",
                        "category": "at_btn-primary",
                        "value": "var(--at-primary-d-1)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Background-color",
                        "group": "ijyfyo"
                    },
                    {
                        "id": "at_btn-primary-border-color--hover",
                        "name": "at-btn-primary-border-color--hover",
                        "type": "color",
                        "category": "at_btn-primary",
                        "value": "var(--at-primary-d-1)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border-color",
                        "group": "ijyfyo"
                    },
                    {
                        "id": "at_btn-primary-outline-color",
                        "name": "at-btn-primary-outline-color",
                        "type": "color",
                        "category": "at_btn-primary",
                        "value": "var(--at-primary)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Color",
                        "group": "yyqeoi",
                        "groupName": "Primary Outline",
                        "groupBase": true
                    },
                    {
                        "id": "at_btn-primary-outline-background",
                        "name": "at-btn-primary-outline-background",
                        "type": "color",
                        "category": "at_btn-primary",
                        "value": "rgba(0,0,0,0)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Background-color",
                        "group": "yyqeoi"
                    },
                    {
                        "id": "at_btn-primary-outline-border-color",
                        "name": "at-btn-primary-outline-border-color",
                        "type": "color",
                        "category": "at_btn-primary",
                        "value": "var(--at-primary)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border-color",
                        "group": "yyqeoi"
                    },
                    {
                        "id": "at_btn-primary-outline-color--hover",
                        "name": "at-btn-primary-outline-color--hover",
                        "type": "color",
                        "category": "at_btn-primary",
                        "value": "var(--at-primary-d-1)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Color",
                        "group": "ahywzd",
                        "groupName": "Primary Outline - Hover",
                        "groupBase": true
                    },
                    {
                        "id": "at_btn-primary-outline-background--hover",
                        "name": "at-btn-primary-outline-background--hover",
                        "type": "color",
                        "category": "at_btn-primary",
                        "value": "rgba(0,0,0,0)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Background-color",
                        "group": "ahywzd"
                    },
                    {
                        "id": "at_btn-primary-outline-border-color--hover",
                        "name": "at-btn-primary-outline-border-color--hover",
                        "type": "color",
                        "category": "at_btn-primary",
                        "value": "var(--at-primary-d-1)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border-color",
                        "group": "ahywzd"
                    },
                    {
                        "id": "at_btn-secondary-color",
                        "name": "at-btn-secondary-color",
                        "type": "color",
                        "category": "at_btn-secondary",
                        "value": "var(--at-secondary-l-6)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Color",
                        "group": "nvvteo",
                        "groupName": "Secondary",
                        "groupBase": true
                    },
                    {
                        "id": "at_btn-secondary-background",
                        "name": "at-btn-secondary-background",
                        "type": "color",
                        "category": "at_btn-secondary",
                        "value": "var(--at-secondary)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Background-color",
                        "group": "nvvteo"
                    },
                    {
                        "id": "at_btn-secondary-border-color",
                        "name": "at-btn-secondary-border-color",
                        "type": "color",
                        "category": "at_btn-secondary",
                        "value": "var(--at-secondary)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border-color",
                        "group": "nvvteo"
                    },
                    {
                        "id": "at_btn-secondary-color--hover",
                        "name": "at-btn-secondary-color--hover",
                        "type": "color",
                        "category": "at_btn-secondary",
                        "value": "var(--at-secondary-l-6)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Color",
                        "group": "yzpyaq",
                        "groupName": "Secondary - Hover",
                        "groupBase": true
                    },
                    {
                        "id": "at_btn-secondary-background--hover",
                        "name": "at-btn-secondary-background--hover",
                        "type": "color",
                        "category": "at_btn-secondary",
                        "value": "var(--at-secondary-d-1)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Background-color",
                        "group": "yzpyaq"
                    },
                    {
                        "id": "at_btn-secondary-border-color--hover",
                        "name": "at-btn-secondary-border-color--hover",
                        "type": "color",
                        "category": "at_btn-secondary",
                        "value": "var(--at-secondary-d-1)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border-color",
                        "group": "yzpyaq"
                    },
                    {
                        "id": "at_btn-secondary-outline-color",
                        "name": "at-btn-secondary-outline-color",
                        "type": "color",
                        "category": "at_btn-secondary",
                        "value": "var(--at-secondary)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Color",
                        "group": "laouzf",
                        "groupName": "Secondary Outline",
                        "groupBase": true
                    },
                    {
                        "id": "at_btn-secondary-outline-background",
                        "name": "at-btn-secondary-outline-background",
                        "type": "color",
                        "category": "at_btn-secondary",
                        "value": "rgba(0,0,0,0)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Background-color",
                        "group": "laouzf"
                    },
                    {
                        "id": "at_btn-secondary-outline-border-color",
                        "name": "at-btn-secondary-outline-border-color",
                        "type": "color",
                        "category": "at_btn-secondary",
                        "value": "var(--at-secondary)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border-color",
                        "group": "laouzf"
                    },
                    {
                        "id": "at_btn-secondary-outline-color--hover",
                        "name": "at-btn-secondary-outline-color--hover",
                        "type": "color",
                        "category": "at_btn-secondary",
                        "value": "var(--at-secondary-d-1)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Color",
                        "group": "cjjuxf",
                        "groupName": "Secondary Outline - Hover",
                        "groupBase": true
                    },
                    {
                        "id": "at_btn-secondary-outline-background--hover",
                        "name": "at-btn-secondary-outline-background--hover",
                        "type": "color",
                        "category": "at_btn-secondary",
                        "value": "rgba(0,0,0,0)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Background-color",
                        "group": "cjjuxf"
                    },
                    {
                        "id": "at_btn-secondary-outline-border-color--hover",
                        "name": "at-btn-secondary-outline-border-color--hover",
                        "type": "color",
                        "category": "at_btn-secondary",
                        "value": "var(--at-secondary-d-1)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border-color",
                        "group": "cjjuxf"
                    },
                    {
                        "id": "at_btn-light-color",
                        "name": "at-btn-light-color",
                        "type": "color",
                        "category": "at_btn-light",
                        "value": "var(--at-primary-d-4)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Color",
                        "group": "hasvxc",
                        "groupName": "Light",
                        "groupBase": true
                    },
                    {
                        "id": "at_btn-light-background",
                        "name": "at-btn-light-background",
                        "type": "color",
                        "category": "at_btn-light",
                        "value": "var(--at-primary-l-5)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Background-color",
                        "group": "hasvxc"
                    },
                    {
                        "id": "at_btn-light-border-color",
                        "name": "at-btn-light-border-color",
                        "type": "color",
                        "category": "at_btn-light",
                        "value": "var(--at-primary-l-5)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border-color",
                        "group": "hasvxc"
                    },
                    {
                        "id": "at_btn-light-color--hover",
                        "name": "at-btn-light-color--hover",
                        "type": "color",
                        "category": "at_btn-light",
                        "value": "var(--at-primary-d-4)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Color",
                        "group": "bnasvt",
                        "groupName": "Light - Hover",
                        "groupBase": true
                    },
                    {
                        "id": "at_btn-light-background--hover",
                        "name": "at-btn-light-background--hover",
                        "type": "color",
                        "category": "at_btn-light",
                        "value": "var(--at-primary-l-4)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Background-color",
                        "group": "bnasvt"
                    },
                    {
                        "id": "at_btn-light-border-color--hover",
                        "name": "at-btn-light-border-color--hover",
                        "type": "color",
                        "category": "at_btn-light",
                        "value": "var(--at-primary-l-4)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border-color",
                        "group": "bnasvt"
                    },
                    {
                        "id": "at_btn-light-outline-color",
                        "name": "at-btn-light-outline-color",
                        "type": "color",
                        "category": "at_btn-light",
                        "value": "var(--at-primary-l-1)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Color",
                        "group": "grvjix",
                        "groupName": "Light Outline",
                        "groupBase": true
                    },
                    {
                        "id": "at_btn-light-outline-background",
                        "name": "at-btn-light-outline-background",
                        "type": "color",
                        "category": "at_btn-light",
                        "value": "rgba(0,0,0,0)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Background-color",
                        "group": "grvjix"
                    },
                    {
                        "id": "at_btn-light-outline-border-color",
                        "name": "at-btn-light-outline-border-color",
                        "type": "color",
                        "category": "at_btn-light",
                        "value": "var(--at-primary-l-5)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border-color",
                        "group": "grvjix"
                    },
                    {
                        "id": "at_btn-light-outline-color--hover",
                        "name": "at-btn-light-outline-color--hover",
                        "type": "color",
                        "category": "at_btn-light",
                        "value": "var(--at-primary)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Color",
                        "group": "mrrxxg",
                        "groupName": "Light Outline - Hover",
                        "groupBase": true
                    },
                    {
                        "id": "at_btn-light-outline-background--hover",
                        "name": "at-btn-light-outline-background--hover",
                        "type": "color",
                        "category": "at_btn-light",
                        "value": "rgba(0,0,0,0)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Background-color",
                        "group": "mrrxxg"
                    },
                    {
                        "id": "at_btn-light-outline-border-color--hover",
                        "name": "at-btn-light-outline-border-color--hover",
                        "type": "color",
                        "category": "at_btn-light",
                        "value": "var(--at-primary-l-3)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border-color",
                        "group": "mrrxxg"
                    },
                    {
                        "id": "at_btn-dark-color",
                        "name": "at-btn-dark-color",
                        "type": "color",
                        "category": "at_btn-dark",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "value": "var(--at-primary-l-6)",
                        "label": "Color",
                        "group": "bzeeyv",
                        "groupName": "Dark",
                        "groupBase": true
                    },
                    {
                        "id": "at_btn-dark-background",
                        "name": "at-btn-dark-background",
                        "type": "color",
                        "category": "at_btn-dark",
                        "value": "var(--at-primary-d-4)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Background-color",
                        "group": "bzeeyv"
                    },
                    {
                        "id": "at_btn-dark-border-color",
                        "name": "at-btn-dark-border-color",
                        "type": "color",
                        "category": "at_btn-dark",
                        "value": "var(--at-primary-d-4)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border-color",
                        "group": "bzeeyv"
                    },
                    {
                        "id": "at_btn-dark-color--hover",
                        "name": "at-btn-dark-color--hover",
                        "type": "color",
                        "category": "at_btn-dark",
                        "value": "var(--at-primary-l-6)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Color",
                        "group": "eqvlyw"
                    },
                    {
                        "id": "at_btn-dark-background--hover",
                        "name": "at-btn-dark-background--hover",
                        "type": "color",
                        "category": "at_btn-dark",
                        "value": "var(--at-primary-d-2)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Background-color",
                        "group": "eqvlyw",
                        "groupName": "Dark - Hover",
                        "groupBase": true
                    },
                    {
                        "id": "at_btn-dark-border-color--hover",
                        "name": "at-btn-dark-border-color--hover",
                        "type": "color",
                        "category": "at_btn-dark",
                        "value": "var(--at-primary-d-2)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border-color",
                        "group": "eqvlyw"
                    },
                    {
                        "id": "at_btn-dark-outline-color",
                        "name": "at-btn-dark-outline-color",
                        "type": "color",
                        "category": "at_btn-dark",
                        "value": "var(--at-primary-d-4)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Color",
                        "group": "pslpys",
                        "groupName": "Dark Outline",
                        "groupBase": true
                    },
                    {
                        "id": "at_btn-dark-outline-background",
                        "name": "at-btn-dark-outline-background",
                        "type": "color",
                        "category": "at_btn-dark",
                        "value": "rgba(0,0,0,0)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Background-color",
                        "group": "pslpys"
                    },
                    {
                        "id": "at_btn-dark-outline-border-color",
                        "name": "at-btn-dark-outline-border-color",
                        "type": "color",
                        "category": "at_btn-dark",
                        "value": "var(--at-primary-d-3)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border-color",
                        "group": "pslpys"
                    },
                    {
                        "id": "at_btn-dark-outline-color--hover",
                        "name": "at-btn-dark-outline-color--hover",
                        "type": "color",
                        "category": "at_btn-dark",
                        "value": "var(--at-primary-d-2)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Color",
                        "group": "senfhg",
                        "groupName": "Dark Outline - Hover",
                        "groupBase": true
                    },
                    {
                        "id": "at_btn-dark-outline-background--hover",
                        "name": "at-btn-dark-outline-background--hover",
                        "type": "color",
                        "category": "at_btn-dark",
                        "value": "rgba(0,0,0,0)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Background-color",
                        "group": "senfhg"
                    },
                    {
                        "id": "at_btn-dark-outline-border-color--hover",
                        "name": "at-btn-dark-outline-border-color--hover",
                        "type": "color",
                        "category": "at_btn-dark",
                        "value": "var(--at-primary-d-1)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border-color",
                        "group": "senfhg"
                    },
                    {
                        "id": "at_btn-small-font-size",
                        "name": "at-btn-small-font-size",
                        "type": "static",
                        "category": "at_btn-size-small",
                        "value": "var(--at-text--xs)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Font-size",
                        "group": "uhfslt",
                        "groupName": "Small",
                        "groupBase": true
                    },
                    {
                        "id": "at_btn-small-padding-block",
                        "name": "at-btn-small-padding-block",
                        "type": "static",
                        "category": "at_btn-size-small",
                        "value": "0.4em",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Padding Block",
                        "group": "uhfslt"
                    },
                    {
                        "id": "at_btn-small-padding-inline",
                        "name": "at-btn-small-padding-inline",
                        "type": "static",
                        "category": "at_btn-size-small",
                        "value": "1em",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Padding Inline",
                        "group": "uhfslt"
                    },
                    {
                        "id": "at_btn-medium-font-size",
                        "name": "at-btn-medium-font-size",
                        "type": "static",
                        "category": "at_btn-size-small",
                        "value": "var(--at-text--s)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Font-size",
                        "group": "yflrkk",
                        "groupName": "Medium",
                        "groupBase": true
                    },
                    {
                        "id": "at_btn-medium-padding-block",
                        "name": "at-btn-medium-padding-block",
                        "type": "static",
                        "category": "at_btn-size-small",
                        "value": "0.5em",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Padding Block",
                        "group": "yflrkk"
                    },
                    {
                        "id": "at_btn-medium-padding-inline",
                        "name": "at-btn-medium-padding-inline",
                        "type": "static",
                        "category": "at_btn-size-small",
                        "value": "1em",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Padding Inline",
                        "group": "yflrkk"
                    },
                    {
                        "id": "at_btn-large-font-size",
                        "name": "at-btn-large-font-size",
                        "type": "static",
                        "category": "at_btn-size-small",
                        "value": "var(--at-text--m)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Font-size",
                        "group": "hyfpaw",
                        "groupName": "Large",
                        "groupBase": true
                    },
                    {
                        "id": "at_btn-large-padding-block",
                        "name": "at-btn-large-padding-block",
                        "type": "static",
                        "category": "at_btn-size-small",
                        "value": "0.6em",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Padding Block",
                        "group": "hyfpaw"
                    },
                    {
                        "id": "at_btn-large-padding-inline",
                        "name": "at-btn-large-padding-inline",
                        "type": "static",
                        "category": "at_btn-size-small",
                        "value": "1em",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Padding Inline",
                        "group": "hyfpaw"
                    },
                    {
                        "id": "at_btn-x-large-font-size",
                        "name": "at-btn-x-large-font-size",
                        "type": "static",
                        "category": "at_btn-size-small",
                        "value": "var(--at-text--l)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Font-size",
                        "group": "kfbwiv",
                        "groupName": "Extra Large",
                        "groupBase": true
                    },
                    {
                        "id": "at_btn-x-large-padding-block",
                        "name": "at-btn-x-large-padding-block",
                        "type": "static",
                        "category": "at_btn-size-small",
                        "value": "0.8em",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Padding Block",
                        "group": "kfbwiv"
                    },
                    {
                        "id": "at_btn-x-large-padding-inline",
                        "name": "at-btn-x-large-padding-inline",
                        "type": "static",
                        "category": "at_btn-size-small",
                        "value": "1em",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Padding Inline",
                        "group": "kfbwiv"
                    },
                    {
                        "id": "at_form-focus-outline-width",
                        "name": "at-form-focus-outline-width",
                        "type": "static",
                        "category": "at_forms",
                        "value": "2px",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Outline-width",
                        "group": "wqrseb",
                        "groupName": "Form Focus",
                        "groupBase": true
                    },
                    {
                        "id": "at_form-label-color",
                        "name": "at-form-label-color",
                        "type": "color",
                        "category": "at_forms",
                        "value": "var(--at-neutral-d-4)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Color",
                        "group": "zuhguk"
                    },
                    {
                        "id": "at_form-label-font-size",
                        "name": "at-form-label-font-size",
                        "type": "static",
                        "category": "at_forms",
                        "value": "var(--at-text--s)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Font-size",
                        "group": "zuhguk",
                        "groupName": "Labels",
                        "groupBase": true
                    },
                    {
                        "id": "at_form-placeholder-color",
                        "name": "at-form-placeholder-color",
                        "type": "color",
                        "category": "at_forms",
                        "value": "var(--at-black-t-4)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Color",
                        "group": "fhhqic"
                    },
                    {
                        "id": "at_form-placeholder-font-size",
                        "name": "at-form-placeholder-font-size",
                        "type": "static",
                        "category": "at_forms",
                        "value": "var(--at-text--s)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Font-size",
                        "group": "fhhqic",
                        "groupName": "Placeholders",
                        "groupBase": true
                    },
                    {
                        "id": "at_form-input-color",
                        "name": "at-form-input-color",
                        "type": "color",
                        "category": "at_forms",
                        "value": "var(--at-neutral-d-3)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Color",
                        "group": "vgewuf"
                    },
                    {
                        "id": "at_form-input-background-color",
                        "name": "at-form-input-background-color",
                        "type": "color",
                        "category": "at_forms",
                        "value": "var(--at-white)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Background-color",
                        "group": "vgewuf"
                    },
                    {
                        "id": "at_form-input-font-size",
                        "name": "at-form-input-font-size",
                        "type": "static",
                        "category": "at_forms",
                        "value": "var(--at-text--s)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Font-size",
                        "group": "vgewuf",
                        "groupName": "Inputs",
                        "groupBase": true
                    },
                    {
                        "id": "at_form-input-border-width",
                        "name": "at-form-input-border-width",
                        "type": "static",
                        "category": "at_forms",
                        "value": "2px",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border-width",
                        "group": "vgewuf"
                    },
                    {
                        "id": "at_form-input-border-color",
                        "name": "at-form-input-border-color",
                        "type": "color",
                        "category": "at_forms",
                        "value": "var(--at-black-t-5)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border-color",
                        "group": "vgewuf"
                    },
                    {
                        "id": "at_form-input-border-radius",
                        "name": "at-form-input-border-radius",
                        "type": "static",
                        "category": "at_forms",
                        "value": "var(--at-radius--xs)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border-radius",
                        "group": "vgewuf"
                    },
                    {
                        "id": "at_form-input-padding-inline",
                        "name": "at-form-input-padding-inline",
                        "type": "static",
                        "category": "at_forms",
                        "value": "0.7em",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Padding Inline",
                        "group": "vgewuf"
                    },
                    {
                        "id": "at_form-input-padding-block",
                        "name": "at-form-input-padding-block",
                        "type": "static",
                        "category": "at_forms",
                        "value": "0.4em",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Padding Block",
                        "group": "vgewuf"
                    },
                    {
                        "id": "at_form-input-color--focus",
                        "name": "at-form-input-color--focus",
                        "type": "color",
                        "category": "at_forms",
                        "value": "var(--at-neutral-d-6)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Color",
                        "group": "uxplir",
                        "groupName": "Inputs - Focus",
                        "groupBase": true
                    },
                    {
                        "id": "at_form-input-background-color-focus",
                        "name": "at-form-input-background-color--focus",
                        "type": "color",
                        "category": "at_forms",
                        "value": "var(--at-neutral-l-5)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Background-color",
                        "group": "uxplir"
                    },
                    {
                        "id": "at_form-input-border-radius--focus",
                        "name": "at-form-input-border-radius--focus",
                        "type": "static",
                        "category": "at_forms",
                        "value": "var(--at-radius--s)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border-radius",
                        "group": "uxplir"
                    },
                    {
                        "id": "at_form-group-padding-inline",
                        "name": "at-form-group-padding-inline",
                        "type": "static",
                        "category": "at_forms",
                        "value": "var(--at-space--xs)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Padding Inline",
                        "group": "epexce",
                        "groupName": "Groups",
                        "groupBase": true
                    },
                    {
                        "id": "at_form-group-padding-block",
                        "name": "at-form-group-padding-block",
                        "type": "static",
                        "category": "at_forms",
                        "value": "var(--at-space--s)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Padding Block",
                        "group": "epexce"
                    },
                    {
                        "id": "at_form-option-color",
                        "name": "at-form-option-color",
                        "type": "color",
                        "category": "at_forms",
                        "value": "var(--at-primary-l-5)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Color",
                        "group": "tcpvzo",
                        "groupName": "Options",
                        "groupBase": true
                    },
                    {
                        "id": "at_form-option-background",
                        "name": "at-form-option-background",
                        "type": "color",
                        "category": "at_forms",
                        "value": "var(--at-primary-d-3)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Background-color",
                        "group": "tcpvzo"
                    },
                    {
                        "id": "at_form-option-off-color",
                        "name": "at-form-option-off-color",
                        "type": "color",
                        "category": "at_forms",
                        "value": "var(--at-neutral)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Off Color",
                        "group": "tcpvzo"
                    },
                    {
                        "id": "at_form-option-on-color",
                        "name": "at-form-option-on-color",
                        "type": "color",
                        "category": "at_forms",
                        "value": "var(--bricks-text-success)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "On Color",
                        "group": "tcpvzo"
                    },
                    {
                        "id": "at_form-valid-color",
                        "name": "at-form-valid-color",
                        "type": "color",
                        "category": "at_forms",
                        "value": "var(--at-success)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Valid Color",
                        "group": "tiiqoh",
                        "groupName": "Form Validation",
                        "groupBase": true
                    },
                    {
                        "id": "at_form-invalid-color",
                        "name": "at-form-invalid-color",
                        "type": "color",
                        "category": "at_forms",
                        "value": "var(--at-danger)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Invalid Color",
                        "group": "tiiqoh"
                    },
                    {
                        "id": "at_link-color",
                        "name": "at-link-color",
                        "type": "color",
                        "category": "at_links",
                        "value": "var(--at-primary)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Color",
                        "group": "slrhmb",
                        "groupName": "Link Color",
                        "groupBase": true
                    },
                    {
                        "id": "at_link-color--hover",
                        "name": "at-link-color--hover",
                        "type": "color",
                        "category": "at_links",
                        "value": "var(--at-primary-l-2)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Color (Hover)",
                        "group": "slrhmb"
                    },
                    {
                        "id": "at_link-color--active",
                        "name": "at-link-color--active",
                        "type": "color",
                        "category": "at_links",
                        "value": "var(--at-secondary)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Color (Active)",
                        "group": "slrhmb"
                    },
                    {
                        "id": "at_mark-background",
                        "name": "at-mark-background",
                        "type": "color",
                        "category": "at_marks",
                        "value": "var(--at-primary)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Background-color",
                        "group": "kymczn",
                        "groupBase": true,
                        "groupName": "Background"
                    },
                    {
                        "id": "at_mark-color",
                        "name": "at-mark-color",
                        "type": "color",
                        "category": "at_marks",
                        "value": "var(--at-primary-l-5)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Color",
                        "group": "kqwysy"
                    },
                    {
                        "id": "at_mark-font-size",
                        "name": "at-mark-font-size",
                        "type": "static",
                        "category": "at_marks",
                        "value": "1em",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Font-size",
                        "group": "kqwysy",
                        "groupName": "Typography",
                        "groupBase": true
                    },
                    {
                        "id": "at_mark-font-weight",
                        "name": "at-mark-font-weight",
                        "type": "static",
                        "category": "at_marks",
                        "value": "inherit",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Font-weight",
                        "group": "kqwysy"
                    },
                    {
                        "id": "at_mark-text-transform",
                        "name": "at-mark-text-transform",
                        "type": "static",
                        "category": "at_marks",
                        "value": "none",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Text-transform",
                        "group": "kqwysy"
                    },
                    {
                        "id": "at_mark-letter-spacing",
                        "name": "at-mark-letter-spacing",
                        "type": "static",
                        "category": "at_marks",
                        "value": "1",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Letter-spacing",
                        "group": "kqwysy"
                    },
                    {
                        "id": "at_mark-line-height",
                        "name": "at-mark-line-height",
                        "type": "static",
                        "category": "at_marks",
                        "value": "1",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Line-height",
                        "group": "kqwysy"
                    },
                    {
                        "id": "at_mark-padding",
                        "name": "at-mark-padding",
                        "type": "static",
                        "category": "at_marks",
                        "value": "var(--at-space--xs)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Padding",
                        "group": "znrxdm",
                        "groupName": "Spacing",
                        "groupBase": true
                    },
                    {
                        "id": "at_mark-inset-block",
                        "name": "at-mark-inset-block",
                        "type": "static",
                        "category": "at_marks",
                        "value": "0",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Inset Block",
                        "group": "znrxdm"
                    },
                    {
                        "id": "at_mark-inset-inline",
                        "name": "at-mark-inset-inline",
                        "type": "static",
                        "category": "at_marks",
                        "value": "0",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Inset Inline",
                        "group": "znrxdm"
                    },
                    {
                        "id": "at_mark-border-width",
                        "name": "at-mark-border-width",
                        "type": "static",
                        "category": "at_marks",
                        "value": "0.1em",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border-width",
                        "group": "ysapkt",
                        "groupName": "Border",
                        "groupBase": true
                    },
                    {
                        "id": "at_mark-border-color",
                        "name": "at-mark-border-color",
                        "type": "color",
                        "category": "at_marks",
                        "value": "var(--at-black-t-5)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border-color",
                        "group": "ysapkt"
                    },
                    {
                        "id": "at_mark-border-radius",
                        "name": "at-mark-border-radius",
                        "type": "static",
                        "category": "at_marks",
                        "value": "var(--at-radius--s)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Border-radius",
                        "group": "ysapkt"
                    },
                    {
                        "id": "at_mark-shadow",
                        "name": "at-mark-shadow",
                        "type": "static",
                        "category": "at_marks",
                        "value": "var(--at-shadow--m)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Shadow",
                        "group": "nxnhnc",
                        "groupName": "Shadows",
                        "groupBase": true
                    },
                    {
                        "id": "at_mark-text-shadow",
                        "name": "at-mark-text-shadow",
                        "type": "static",
                        "category": "at_marks",
                        "value": "var(--at-shadow--s)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Text Shadow",
                        "group": "nxnhnc"
                    },
                    {
                        "id": "at_mark-transform",
                        "name": "at-mark-transform",
                        "type": "static",
                        "category": "at_marks",
                        "value": "rotate(1deg)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Transform",
                        "group": "kcofvx",
                        "groupName": "Transform",
                        "groupBase": true
                    },
                    {
                        "id": "at_mark-background-transform",
                        "name": "at-mark-background-transform",
                        "type": "static",
                        "category": "at_marks",
                        "value": "skewx(5deg)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Background-transform",
                        "group": "kcofvx"
                    },
                    {
                        "id": "at_duration--slow",
                        "name": "at-duration--slow",
                        "type": "static",
                        "category": "at_filter",
                        "value": "1.5s",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Slow",
                        "group": "nytugm"
                    },
                    {
                        "id": "at_duration--medium",
                        "name": "at-duration--medium",
                        "type": "static",
                        "category": "at_filter",
                        "value": "0.5s",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Medium",
                        "group": "nytugm"
                    },
                    {
                        "id": "at_duration--fast",
                        "name": "at-duration--fast",
                        "type": "static",
                        "category": "at_filter",
                        "value": "0.2s",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Fast",
                        "group": "nytugm",
                        "groupName": "Duration",
                        "groupBase": true
                    },
                    {
                        "id": "at_distance--s",
                        "name": "at-distance--s",
                        "type": "static",
                        "category": "at_filter",
                        "value": "4px",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "s",
                        "group": "xkxncz",
                        "groupName": "Distance",
                        "groupBase": true
                    },
                    {
                        "id": "at_distance--m",
                        "name": "at-distance--m",
                        "type": "static",
                        "category": "at_filter",
                        "value": "15px",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "m",
                        "group": "xkxncz"
                    },
                    {
                        "id": "at_distance--l",
                        "name": "at-distance--l",
                        "type": "static",
                        "category": "at_filter",
                        "value": "40px",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "l",
                        "group": "xkxncz"
                    },
                    {
                        "id": "at_entrance-animation--easing",
                        "name": "at-entrance-animation--easing",
                        "type": "static",
                        "value": "ease-in-out",
                        "category": "at_entrance-animations",
                        "label": "Easing",
                        "group": "owhezd",
                        "at_framework": true,
                        "at_version": "1.2.0"
                    },
                    {
                        "id": "at_entrance-animation--delay",
                        "name": "at-entrance-animation--delay",
                        "type": "static",
                        "category": "at_entrance-animations",
                        "group": "owhezd",
                        "groupName": "Entrance Animations",
                        "groupBase": true,
                        "label": "Delay",
                        "value": "var(--at-duration--fast, 200ms)",
                        "at_framework": true,
                        "at_version": "1.2.0"
                    },
                    {
                        "id": "at_entrance-animation--distance",
                        "name": "at-entrance-animation--distance",
                        "type": "static",
                        "category": "at_entrance-animations",
                        "group": "owhezd",
                        "label": "Distance",
                        "value": "var(--at-distance--m, 15px)",
                        "at_framework": true,
                        "at_version": "1.2.0"
                    },
                    {
                        "id": "at_entrance-animation--duration",
                        "name": "at-entrance-animation--duration",
                        "type": "static",
                        "category": "at_entrance-animations",
                        "group": "owhezd",
                        "label": "Duration",
                        "value": "var(--at-duration--fast, 200ms)",
                        "at_framework": true,
                        "at_version": "1.2.0"
                    },
                    {
                        "id": "at_entrance-animation-stagger--delay",
                        "name": "at-entrance-animation-stagger--delay",
                        "type": "static",
                        "category": "at_entrance-animations",
                        "group": "owhezd",
                        "label": "Stagger Delay",
                        "value": "var(--at-entrance-animation--delay)",
                        "at_framework": true,
                        "at_version": "1.2.0"
                    },
                    {
                        "id": "at_entrance-animation--image-easing",
                        "name": "at-entrance-animation--image-easing",
                        "type": "static",
                        "value": "cubic-bezier(0.455, 0.03, 0.515, 0.955)",
                        "category": "at_entrance-animations",
                        "label": "Image Easing",
                        "group": "rxjcok",
                        "groupBase": true,
                        "groupName": "Reveal Animations",
                        "at_framework": true,
                        "at_version": "1.2.0"
                    },
                    {
                        "id": "at_entrance-animation--layer-easing",
                        "name": "at-entrance-animation--layer-easing",
                        "type": "static",
                        "value": "cubic-bezier(0.77, 0, 0.175, 1)",
                        "category": "at_entrance-animations",
                        "label": "Layer Easing",
                        "group": "rxjcok",
                        "at_framework": true,
                        "at_version": "1.2.0"
                    },
                    {
                        "id": "at_entrance-animation-reveal--duration",
                        "name": "at-entrance-animation-reveal--duration",
                        "type": "static",
                        "category": "at_entrance-animations",
                        "group": "rxjcok",
                        "label": "Reveal - Duration",
                        "value": "var(--at-duration--medium, 500ms)",
                        "at_framework": true,
                        "at_version": "1.2.0"
                    },
                    {
                        "id": "at_entrance-animation-reveal-with-layer--duration",
                        "name": "at-entrance-animation-reveal-with-layer--duration",
                        "type": "static",
                        "category": "at_entrance-animations",
                        "group": "rxjcok",
                        "label": "Reveal with Layer - Duration",
                        "value": "var(--at-duration--slow, 1500ms)",
                        "at_framework": true,
                        "at_version": "1.2.0"
                    },
                    {
                        "id": "at_entrance-animation--layer-bg-color",
                        "name": "at-entrance-animation--layer-bg-color",
                        "type": "color",
                        "value": "var(--at-primary)",
                        "category": "at_entrance-animations",
                        "group": "rxjcok",
                        "label": "Layer Background Color",
                        "at_framework": true,
                        "at_version": "1.2.0"
                    },
                    {
                        "id": "at_focus-outline-color",
                        "name": "at-focus-outline-color",
                        "type": "color",
                        "category": "at_a11y",
                        "value": "var(--at-neutral)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Color",
                        "group": "clunmn",
                        "groupName": "Focus Outline",
                        "groupBase": true
                    },
                    {
                        "id": "at_focus-outline-width",
                        "name": "at-focus-outline-width",
                        "type": "static",
                        "category": "at_a11y",
                        "value": "3px",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Width",
                        "group": "clunmn"
                    },
                    {
                        "id": "at_focus-outline-offset",
                        "name": "at-focus-outline-offset",
                        "type": "static",
                        "category": "at_a11y",
                        "value": "3px",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Offset",
                        "group": "clunmn"
                    },
                    {
                        "id": "at_svg--arrow",
                        "name": "at-svg--arrow",
                        "type": "static",
                        "category": "at_svg",
                        "value": "url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgd2lkdGg9IjI0IiBoZWlnaHQ9IjI0IiBjb2xvcj0iIzAwMDAwMCIgZmlsbD0ibm9uZSI+CiAgICA8cGF0aCBkPSJNOS4wMDAwNSA2TDE1IDEyTDkgMTgiIHN0cm9rZT0iY3VycmVudENvbG9yIiBzdHJva2Utd2lkdGg9IjEuNSIgc3Ryb2tlLW1pdGVybGltaXQ9IjE2IiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiIC8+Cjwvc3ZnPg==)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Arrow",
                        "group": "bjcxmi",
                        "groupName": "Icons",
                        "groupBase": true
                    },
                    {
                        "id": "at_svg--check",
                        "name": "at-svg--check",
                        "type": "static",
                        "category": "at_svg",
                        "value": "url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgd2lkdGg9IjI0IiBoZWlnaHQ9IjI0IiBjb2xvcj0iIzAwMDAwMCIgZmlsbD0ibm9uZSI+CiAgICA8cGF0aCBkPSJNNC4yNSAxMy41TDguNzUgMThMMTkuNzUgNiIgc3Ryb2tlPSJjdXJyZW50Q29sb3IiIHN0cm9rZS13aWR0aD0iMS41IiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiIC8+Cjwvc3ZnPg==)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Check",
                        "group": "bjcxmi"
                    },
                    {
                        "id": "at_svg--cross",
                        "name": "at-svg--cross",
                        "type": "static",
                        "category": "at_svg",
                        "value": "url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgd2lkdGg9IjI0IiBoZWlnaHQ9IjI0IiBjb2xvcj0iIzAwMDAwMCIgZmlsbD0ibm9uZSI+CiAgICA8cGF0aCBkPSJNNS4wMDA0OSA0Ljk5OTg4TDE5LjAwMDUgMTguOTk5OSIgc3Ryb2tlPSJjdXJyZW50Q29sb3IiIHN0cm9rZS13aWR0aD0iMS41IiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiIC8+CiAgICA8cGF0aCBkPSJNMTkuMDAwNSA0Ljk5OTg4TDUuMDAwNDkgMTguOTk5OSIgc3Ryb2tlPSJjdXJyZW50Q29sb3IiIHN0cm9rZS13aWR0aD0iMS41IiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiIC8+Cjwvc3ZnPg==)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Cross",
                        "group": "bjcxmi"
                    },
                    {
                        "id": "at_svg--heart",
                        "name": "at-svg--heart",
                        "type": "static",
                        "category": "at_svg",
                        "value": "url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgd2lkdGg9IjI0IiBoZWlnaHQ9IjI0IiBjb2xvcj0iIzAwMDAwMCIgZmlsbD0ibm9uZSI+CiAgICA8cGF0aCBkPSJNMTkuNDYyNiAzLjk5NDE1QzE2Ljc4MDkgMi4zNDkyMyAxNC40NDA0IDMuMDEyMTEgMTMuMDM0NCA0LjA2ODAxQzEyLjQ1NzggNC41MDA5NiAxMi4xNjk2IDQuNzE3NDMgMTIgNC43MTc0M0MxMS44MzA0IDQuNzE3NDMgMTEuNTQyMiA0LjUwMDk2IDEwLjk2NTYgNC4wNjgwMUM5LjU1OTYyIDMuMDEyMTEgNy4yMTkwOSAyLjM0OTIzIDQuNTM3NDQgMy45OTQxNUMxLjAxODA3IDYuMTUyOTQgMC4yMjE3MjEgMTMuMjc0OSA4LjMzOTUzIDE5LjI4MzRDOS44ODU3MiAyMC40Mjc4IDEwLjY1ODggMjEgMTIgMjFDMTMuMzQxMiAyMSAxNC4xMTQzIDIwLjQyNzggMTUuNjYwNSAxOS4yODM0QzIzLjc3ODMgMTMuMjc0OSAyMi45ODE5IDYuMTUyOTQgMTkuNDYyNiAzLjk5NDE1WiIgc3Ryb2tlPSJjdXJyZW50Q29sb3IiIHN0cm9rZS13aWR0aD0iMS41IiBzdHJva2UtbGluZWNhcD0icm91bmQiIC8+Cjwvc3ZnPg==)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Heart",
                        "group": "bjcxmi"
                    },
                    {
                        "id": "at_svg--delete",
                        "name": "at-svg--delete",
                        "type": "static",
                        "category": "at_svg",
                        "value": "url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgd2lkdGg9IjI0IiBoZWlnaHQ9IjI0IiBjb2xvcj0iIzAwMDAwMCIgZmlsbD0ibm9uZSI+CiAgICA8cGF0aCBkPSJNMTkuNSA1LjVMMTguNSAyMkg1LjVMNC41IDUuNSIgc3Ryb2tlPSJjdXJyZW50Q29sb3IiIHN0cm9rZS13aWR0aD0iMS41IiBzdHJva2UtbGluZWpvaW49InJvdW5kIiAvPgogICAgPHBhdGggZD0iTTIgNS41SDhNMjIgNS41SDE2TTE2IDUuNUwxNC41IDJIOS41TDggNS41TTE2IDUuNUg4IiBzdHJva2U9ImN1cnJlbnRDb2xvciIgc3Ryb2tlLXdpZHRoPSIxLjUiIHN0cm9rZS1saW5lam9pbj0icm91bmQiIC8+Cjwvc3ZnPg==)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Delete",
                        "group": "bjcxmi"
                    },
                    {
                        "id": "at_svg-cancel-solid",
                        "name": "at-svg-cancel-solid",
                        "type": "static",
                        "category": "at_svg",
                        "value": "url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgd2lkdGg9IjI0IiBoZWlnaHQ9IjI0IiBjb2xvcj0iIzAwMDAwMCIgZmlsbD0ibm9uZSI+CiAgICA8cGF0aCBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGNsaXAtcnVsZT0iZXZlbm9kZCIgZD0iTTEyIDIyLjc1QzYuMDYyOTQgMjIuNzUgMS4yNSAxNy45MzcxIDEuMjUgMTJDMS4yNSA2LjA2Mjk0IDYuMDYyOTQgMS4yNSAxMiAxLjI1QzE3LjkzNzEgMS4yNSAyMi43NSA2LjA2Mjk0IDIyLjc1IDEyQzIyLjc1IDE3LjkzNzEgMTcuOTM3MSAyMi43NSAxMiAyMi43NVpNMTUuMjkzIDcuMjkyODVMMTEuOTk5OCAxMC41ODU4TDguNzA3MTQgNy4yOTMzN0w3LjI5Mjk3IDguNzA3NjNMMTAuNTg1NSAxMkw3LjI5Mjk3IDE1LjI5MjNMOC43MDcxNCAxNi43MDY2TDExLjk5OTggMTMuNDE0MUwxNS4yOTMgMTYuNzA3MUwxNi43MDcxIDE1LjI5MjhMMTMuNDE0IDEyTDE2LjcwNzEgOC43MDcxMUwxNS4yOTMgNy4yOTI4NVoiIGZpbGw9IiMwMDAwMDAiPjwvcGF0aD4KPC9zdmc+)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Cancel",
                        "group": "cecsbd",
                        "groupName": "Checkbox",
                        "groupBase": true
                    },
                    {
                        "id": "at_svg-check-solid",
                        "name": "at-svg-check-solid",
                        "type": "static",
                        "category": "at_svg",
                        "value": "url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgd2lkdGg9IjI0IiBoZWlnaHQ9IjI0IiBjb2xvcj0iIzAwMDAwMCIgZmlsbD0ibm9uZSI+CiAgICA8cGF0aCBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGNsaXAtcnVsZT0iZXZlbm9kZCIgZD0iTTEyIDIyLjc1QzYuMDYyOTQgMjIuNzUgMS4yNSAxNy45MzcxIDEuMjUgMTJDMS4yNSA2LjA2Mjk0IDYuMDYyOTQgMS4yNSAxMiAxLjI1QzE3LjkzNzEgMS4yNSAyMi43NSA2LjA2Mjk0IDIyLjc1IDEyQzIyLjc1IDE3LjkzNzEgMTcuOTM3MSAyMi43NSAxMiAyMi43NVpNMTUuMjQ3MyA3LjkzOTMzTDEwLjQ1MyAxMy4xNjk0TDguNjkxNTYgMTEuNDA3OUw3LjI3NzM0IDEyLjgyMjJMMTAuNTE1OSAxNi4wNjA3TDE2LjcyMTYgOS4yOTA3OEwxNS4yNDczIDcuOTM5MzNaIiBmaWxsPSIjMDAwMDAwIj48L3BhdGg+Cjwvc3ZnPg==)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Check",
                        "group": "cecsbd"
                    },
                    {
                        "id": "at_svg-radio-selected",
                        "name": "at-svg-radio-selected",
                        "type": "static",
                        "category": "at_svg",
                        "value": "url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgd2lkdGg9IjI0IiBoZWlnaHQ9IjI0IiBjb2xvcj0iIzAwMDAwMCIgZmlsbD0ibm9uZSI+CiAgICA8cGF0aCBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGNsaXAtcnVsZT0iZXZlbm9kZCIgZD0iTTEyIDMuNzVDNy40NDM2NSAzLjc1IDMuNzUgNy40NDM2NSAzLjc1IDEyQzMuNzUgMTYuNTU2MyA3LjQ0MzY1IDIwLjI1IDEyIDIwLjI1QzE2LjU1NjMgMjAuMjUgMjAuMjUgMTYuNTU2MyAyMC4yNSAxMkMyMC4yNSA3LjQ0MzY1IDE2LjU1NjMgMy43NSAxMiAzLjc1Wk0yLjI1IDEyQzIuMjUgNi42MTUyMiA2LjYxNTIyIDIuMjUgMTIgMi4yNUMxNy4zODQ4IDIuMjUgMjEuNzUgNi42MTUyMiAyMS43NSAxMkMyMS43NSAxNy4zODQ4IDE3LjM4NDggMjEuNzUgMTIgMjEuNzVDNi42MTUyMiAyMS43NSAyLjI1IDE3LjM4NDggMi4yNSAxMloiIGZpbGw9IiMwMDAwMDAiPjwvcGF0aD4KICAgIDxwYXRoIGQ9Ik03LjI1IDEyQzcuMjUgOS4zNzY2NSA5LjM3NjY1IDcuMjUgMTIgNy4yNUMxNC42MjM0IDcuMjUgMTYuNzUgOS4zNzY2NSAxNi43NSAxMkMxNi43NSAxNC42MjM0IDE0LjYyMzQgMTYuNzUgMTIgMTYuNzVDOS4zNzY2NSAxNi43NSA3LjI1IDE0LjYyMzQgNy4yNSAxMloiIGZpbGw9IiMwMDAwMDAiPjwvcGF0aD4KPC9zdmc+)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Selected",
                        "group": "vsehuj",
                        "groupName": "Radio",
                        "groupBase": true
                    },
                    {
                        "id": "at_svg-radio-unselected",
                        "name": "at-svg-radio-unselected",
                        "type": "static",
                        "category": "at_svg",
                        "value": "url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgd2lkdGg9IjI0IiBoZWlnaHQ9IjI0IiBjb2xvcj0iIzAwMDAwMCIgZmlsbD0ibm9uZSI+CiAgICA8Y2lyY2xlIGN4PSIxMiIgY3k9IjEyIiByPSI5IiBzdHJva2U9IiMwMDAwMDAiIHN0cm9rZS13aWR0aD0iMS41IiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiPjwvY2lyY2xlPgogICAgPGNpcmNsZSBjeD0iMTIiIGN5PSIxMiIgcj0iNCIgc3Ryb2tlPSIjMDAwMDAwIiBzdHJva2Utd2lkdGg9IjEuNSIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIj48L2NpcmNsZT4KPC9zdmc+)",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "label": "Unselected",
                        "group": "vsehuj"
                    }
                    ]',
            "global_classes_cat" => '
                [
                    {
                        "id": "at_colorset",
                        "name": "Color Set",
                        "at_framework": true,
                        "at_version": "1.0.0"
                    },
                    {
                        "id": "at_focus",
                        "name": "Focus",
                        "at_framework": true,
                        "at_version": "1.0.0"
                    },
                    {
                        "id": "at_ul",
                        "name": "Unordered List",
                        "at_framework": true,
                        "at_version": "1.0.0"
                    },
                    {
                        "id": "at_line-clamp",
                        "name": "Line Clamp",
                        "at_framework": true,
                        "at_version": "1.0.0"
                    },
                    {
                        "id": "at_marks",
                        "name": "Marks",
                        "at_framework": true,
                        "at_version": "1.0.0"
                    },
                    {
                        "id": "at_accessibility",
                        "name": "Accessibility",
                        "at_framework": true,
                        "at_version": "1.0.0"
                    },
                    {
                        "id": "at_forms",
                        "name": "Forms",
                        "at_framework": true,
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "at_placeholder",
                        "name": "Placeholders",
                        "at_framework": true,
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "at_entrance-animations",
                        "name": "Entrance Animations",
                        "at_framework": true,
                        "at_version": "1.2.0"
                    }
                ]',
            "global_classes" => '
            [
  {
    "id": "at_colorset",
    "name": "at-colorset",
    "settings": {
      "_cssCustom": "/*\\nCOLOR SETS\\n*/\\n\\n/* Do not use :where on this rule or it will get overridden by .at-section--alt-odd and .at-section--alt-even */\\n[class*=colorset--] {\\n  color: var(--text-color);\\n  background: var(--background);\\n}\\n\\n[class*=colorset--] [class*=accent]{\\n  color: var(--accent-text-color);\\n}\\n\\n/* wherever possible, weaken selectors with :where */\\n:where([class*=colorset--] h1, [class*=colorset--] h2, [class*=colorset--] h3, [class*=colorset--] h4) {\\n  color: var(--heading-color);\\n}\\n\\n:where([class*=colorset--]) :is(.brxe-text-link, .brxe-text a, .brxe-text-basic a) {\\n  color: var(--link-color, var(--at-link-color));\\n}\\n\\n:where([class*=colorset--]) :is(.brxe-text-link, .brxe-text a, .brxe-text-basic a):is(:hover, :focus-visible) {\\n  color: var(--link-color--hover, var(--at-link-color--hover));\\n}\\n\\n:where([class*=colorset--]) .brxe-text-link:active {\\n  color: var(--link-color--active, var(--at-link-color--active));\\n}\\n\\n/* !IMPORTANT \\nAny class that contains the follwing wrapper class names get targeted \\n*/\\n:where([class*=colorset--] [class*=header-wrapper]){\\n  color: var(--header-wrapper-color);\\n  background: var(--header-wrapper-background);\\n}\\n:where([class*=colorset--] [class*=body-wrapper]){\\n  color: var(--body-wrapper-color);\\n  background: var(--body-wrapper-background);\\n}\\n:where([class*=colorset--] [class*=footer-wrapper]){\\n  color: var(--footer-wrapper-color);\\n  background: var(--footer-wrapper-background);\\n}\\n:where([class*=colorset--] [class*=media-wrapper]){\\n  color: var(--media-wrapper-color);\\n  background: var(--media-wrapper-background);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--neutral",
    "name": "at-colorset--neutral",
    "settings": {
      "_cssCustom": ".at-colorset--neutral {\\n  --text-color: var(--at-neutral-l-5);\\n  --background: var(--at-neutral);\\n  --heading-color: var(--at-neutral-l-6);\\n  --accent-text-color: var(--at-secondary);\\n  \\n  --link-color: var(--at-primary);\\n  --link-color--hover: var(--at-primary-l-6);\\n  --link-color--active: var(--at-primary-l-6);\\n  \\n  --header-wrapper-color: var(--at-neutral-d-6);\\n  --header-wrapper-background: var(--at-black-t-5);\\n\\n  --body-wrapper-color: var(--at-neutral-l-5);\\n  --body-wrapper-background: var(--at-black-t-5);\\n\\n  --footer-wrapper-color: var(--at-neutral-d-6);\\n  --footer-wrapper-background: var(--at-black-t-5);\\n\\n  --media-wrapper-color: var(--at-neutral-d-6);\\n  --media-wrapper-background: var(--at-black-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--neutral-d-1",
    "name": "at-colorset--neutral-d-1",
    "settings": {
      "_cssCustom": ".at-colorset--neutral-d-1 {\\n  --text-color: var(--at-neutral-l-5);\\n  --background: var(--at-neutral-d-1);\\n  --heading-color: var(--at-neutral-l-6);\\n  --accent-text-color: var(--at-secondary);\\n  \\n  --link-color: var(--at-primary);\\n  --link-color--hover: var(--at-primary-l-6);\\n  --link-color--active: var(--at-primary-l-6);\\n  \\n  --header-wrapper-color: var(--at-neutral-l-6);\\n  --header-wrapper-background: var(--at-white-t-5);\\n\\n  --body-wrapper-color: var(--at-neutral-l-5);\\n  --body-wrapper-background: var(--at-white-t-5);\\n\\n  --footer-wrapper-color: var(--at-neutral-l-6);\\n  --footer-wrapper-background: var(--at-white-t-5);\\n\\n  --media-wrapper-color: var(--at-neutral-l-6);\\n  --media-wrapper-background: var(--at-white-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--neutral-d-2",
    "name": "at-colorset--neutral-d-2",
    "settings": {
      "_cssCustom": ".at-colorset--neutral-d-2 {\\n  --text-color: var(--at-neutral-l-5);\\n  --background: var(--at-neutral-d-2);\\n  --heading-color: var(--at-neutral-l-6);\\n  --accent-text-color: var(--at-secondary);\\n  \\n  --link-color: var(--at-primary);\\n  --link-color--hover: var(--at-primary-l-6);\\n  --link-color--active: var(--at-primary-l-6);\\n  \\n    --header-wrapper-color: var(--at-neutral-l-6);\\n  --header-wrapper-background: var(--at-white-t-5);\\n\\n  --body-wrapper-color: var(--at-neutral-l-5);\\n  --body-wrapper-background: var(--at-white-t-5);\\n\\n  --footer-wrapper-color: var(--at-neutral-l-6);\\n  --footer-wrapper-background: var(--at-white-t-5);\\n\\n  --media-wrapper-color: var(--at-neutral-l-6);\\n  --media-wrapper-background: var(--at-white-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--neutral-d-3",
    "name": "at-colorset--neutral-d-3",
    "settings": {
      "_cssCustom": ".at-colorset--neutral-d-3 {\\n  --text-color: var(--at-neutral-l-5);\\n  --background: var(--at-neutral-d-3);\\n  --heading-color: var(--at-neutral-l-6);\\n  --accent-text-color: var(--at-secondary);\\n  \\n  --link-color: var(--at-primary);\\n  --link-color--hover: var(--at-primary-l-6);\\n  --link-color--active: var(--at-primary-l-6);\\n  \\n    --header-wrapper-color: var(--at-neutral-l-6);\\n  --header-wrapper-background: var(--at-white-t-5);\\n\\n  --body-wrapper-color: var(--at-neutral-l-5);\\n  --body-wrapper-background: var(--at-white-t-5);\\n\\n  --footer-wrapper-color: var(--at-neutral-l-6);\\n  --footer-wrapper-background: var(--at-white-t-5);\\n\\n  --media-wrapper-color: var(--at-neutral-l-6);\\n  --media-wrapper-background: var(--at-white-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--neutral-d-4",
    "name": "at-colorset--neutral-d-4",
    "settings": {
      "_cssCustom": ".at-colorset--neutral-d-4 {\\n  --text-color: var(--at-neutral-l-5);\\n  --background: var(--at-neutral-d-4);\\n  --heading-color: var(--at-neutral-l-6);\\n  --accent-text-color: var(--at-secondary);\\n  \\n  --link-color: var(--at-primary);\\n  --link-color--hover: var(--at-primary-l-6);\\n  --link-color--active: var(--at-primary-l-6);\\n  \\n    --header-wrapper-color: var(--at-neutral-l-6);\\n  --header-wrapper-background: var(--at-white-t-5);\\n\\n  --body-wrapper-color: var(--at-neutral-l-5);\\n  --body-wrapper-background: var(--at-white-t-5);\\n\\n  --footer-wrapper-color: var(--at-neutral-l-6);\\n  --footer-wrapper-background: var(--at-white-t-5);\\n\\n  --media-wrapper-color: var(--at-neutral-l-6);\\n  --media-wrapper-background: var(--at-white-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--neutral-d-5",
    "name": "at-colorset--neutral-d-5",
    "settings": {
      "_cssCustom": ".at-colorset--neutral-d-5 {\\n  --text-color: var(--at-neutral-l-5);\\n  --background: var(--at-neutral-d-5);\\n  --heading-color: var(--at-neutral-l-6);\\n  --accent-text-color: var(--at-secondary);\\n  \\n  --link-color: var(--at-primary);\\n  --link-color--hover: var(--at-primary-l-6);\\n  --link-color--active: var(--at-primary-l-6);\\n  \\n    --header-wrapper-color: var(--at-neutral-l-6);\\n  --header-wrapper-background: var(--at-white-t-5);\\n\\n  --body-wrapper-color: var(--at-neutral-l-5);\\n  --body-wrapper-background: var(--at-white-t-5);\\n\\n  --footer-wrapper-color: var(--at-neutral-l-6);\\n  --footer-wrapper-background: var(--at-white-t-5);\\n\\n  --media-wrapper-color: var(--at-neutral-l-6);\\n  --media-wrapper-background: var(--at-white-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--neutral-d-6",
    "name": "at-colorset--neutral-d-6",
    "settings": {
      "_cssCustom": ".at-colorset--neutral-d-6 {\\n  --text-color: var(--at-neutral-l-5);\\n  --background: var(--at-neutral-d-6);\\n  --heading-color: var(--at-neutral-l-6);\\n  --accent-text-color: var(--at-secondary);\\n  \\n  --link-color: var(--at-primary);\\n  --link-color--hover: var(--at-primary-l-6);\\n  --link-color--active: var(--at-primary-l-6);\\n  \\n    --header-wrapper-color: var(--at-neutral-l-6);\\n  --header-wrapper-background: var(--at-white-t-5);\\n\\n  --body-wrapper-color: var(--at-neutral-l-5);\\n  --body-wrapper-background: var(--at-white-t-5);\\n\\n  --footer-wrapper-color: var(--at-neutral-l-6);\\n  --footer-wrapper-background: var(--at-white-t-5);\\n\\n  --media-wrapper-color: var(--at-neutral-l-6);\\n  --media-wrapper-background: var(--at-white-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--neutral-l-1",
    "name": "at-colorset--neutral-l-1",
    "settings": {
      "_cssCustom": ".at-colorset--neutral-l-1 {\\n  --text-color: var(--at-neutral-d-5);\\n  --background: var(--at-neutral-l-1);\\n  --heading-color: var(--at-neutral-d-6);\\n  --accent-text-color: var(--at-secondary);\\n  \\n  --link-color: var(--at-primary);\\n  --link-color--hover: var(--at-primary-d-6);\\n  --link-color--active: var(--at-primary-l-6);\\n  \\n  --header-wrapper-color: var(--at-neutral-d-6);\\n  --header-wrapper-background: var(--at-black-t-5);\\n\\n  --body-wrapper-color: var(--at-neutral-l-5);\\n  --body-wrapper-background: var(--at-black-t-5);\\n\\n  --footer-wrapper-color: var(--at-neutral-d-6);\\n  --footer-wrapper-background: var(--at-black-t-5);\\n\\n  --media-wrapper-color: var(--at-neutral-d-6);\\n  --media-wrapper-background: var(--at-black-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--neutral-l-2",
    "name": "at-colorset--neutral-l-2",
    "settings": {
      "_cssCustom": ".at-colorset--neutral-l-2 {\\n  --text-color: var(--at-neutral-d-5);\\n  --background: var(--at-neutral-l-2);\\n  --heading-color: var(--at-neutral-d-6);\\n  --accent-text-color: var(--at-secondary-d-2);\\n  \\n  --link-color: var(--at-primary-d-2);\\n  --link-color--hover: var(--at-primary-d-6);\\n  --link-color--active: var(--at-primary-l-6);\\n  \\n  --header-wrapper-color: var(--at-neutral-d-6);\\n  --header-wrapper-background: var(--at-black-t-5);\\n\\n  --body-wrapper-color: var(--at-neutral-l-5);\\n  --body-wrapper-background: var(--at-black-t-5);\\n\\n  --footer-wrapper-color: var(--at-neutral-d-6);\\n  --footer-wrapper-background: var(--at-black-t-5);\\n\\n  --media-wrapper-color: var(--at-neutral-d-6);\\n  --media-wrapper-background: var(--at-black-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--neutral-l-3",
    "name": "at-colorset--neutral-l-3",
    "settings": {
      "_cssCustom": ".at-colorset--neutral-l-3 {\\n  --text-color: var(--at-neutral-d-5);\\n  --background: var(--at-neutral-l-3);\\n  --heading-color: var(--at-neutral-d-6);\\n  --accent-text-color: var(--at-secondary-d-2);\\n  \\n  --link-color: var(--at-primary-d-2);\\n  --link-color--hover: var(--at-primary-d-6);\\n  --link-color--active: var(--at-primary-l-6);\\n  \\n  --header-wrapper-color: var(--at-neutral-d-6);\\n  --header-wrapper-background: var(--at-black-t-5);\\n\\n  --body-wrapper-color: var(--at-neutral-d-5);\\n  --body-wrapper-background: var(--at-black-t-5);\\n\\n  --footer-wrapper-color: var(--at-neutral-d-6);\\n  --footer-wrapper-background: var(--at-black-t-5); \\n\\n  --media-wrapper-color: var(--at-neutral-d-6);\\n   --media-wrapper-background: var(--at-black-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--neutral-l-4",
    "name": "at-colorset--neutral-l-4",
    "settings": {
      "_cssCustom": ".at-colorset--neutral-l-4 {\\n  --text-color: var(--at-neutral-d-5);\\n  --background: var(--at-neutral-l-4);\\n  --heading-color: var(--at-neutral-d-6);\\n  --accent-text-color: var(--at-secondary-d-2);\\n  \\n  --link-color: var(--at-primary-d-2);\\n  --link-color--hover: var(--at-primary-d-6);\\n  --link-color--active: var(--at-primary-l-6);\\n  \\n    --header-wrapper-color: var(--at-neutral-d-6);\\n  --header-wrapper-background: var(--at-black-t-5);\\n\\n  --body-wrapper-color: var(--at-neutral-d-5);\\n  --body-wrapper-background: var(--at-black-t-5);\\n\\n  --footer-wrapper-color: var(--at-neutral-d-6);\\n  --footer-wrapper-background: var(--at-black-t-5);\\n\\n  --media-wrapper-color: var(--at-neutral-d-6);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--neutral-l-5",
    "name": "at-colorset--neutral-l-5",
    "settings": {
      "_cssCustom": ".at-colorset--neutral-l-5 {\\n  --text-color: var(--at-neutral-d-5);\\n  --background: var(--at-neutral-l-5);\\n  --heading-color: var(--at-neutral-d-6);\\n  --accent-text-color: var(--at-secondary-d-2);\\n  \\n  --link-color: var(--at-primary-d-2);\\n  --link-color--hover: var(--at-primary-d-6);\\n  --link-color--active: var(--at-primary-l-6);\\n  \\n    --header-wrapper-color: var(--at-neutral-d-6);\\n  --header-wrapper-background: var(--at-black-t-5);\\n\\n  --body-wrapper-color: var(--at-neutral-d-5);\\n  --body-wrapper-background: var(--at-black-t-5);\\n\\n  --footer-wrapper-color: var(--at-neutral-d-6);\\n  --footer-wrapper-background: var(--at-black-t-5); \\n\\n  --media-wrapper-color: var(--at-neutral-d-6);\\n   --media-wrapper-background: var(--at-black-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--neutral-l-6",
    "name": "at-colorset--neutral-l-6",
    "settings": {
      "_cssCustom": ".at-colorset--neutral-l-6 {\\n  --text-color: var(--at-neutral-d-5);\\n  --background: var(--at-neutral-l-6);\\n  --heading-color: var(--at-neutral-d-6);\\n  --accent-text-color: var(--at-secondary-d-2);\\n\\n  --link-color: var(--at-primary-d-2);\\n  --link-color--hover: var(--at-primary-d-6);\\n  --link-color--active: var(--at-primary-l-6);\\n\\n  --header-wrapper-color: var(--at-neutral-d-6);\\n  --header-wrapper-background: var(--at-black-t-5);\\n\\n  --body-wrapper-color: var(--at-neutral-d-5);\\n  --body-wrapper-background: var(--at-black-t-5);\\n\\n  --footer-wrapper-color: var(--at-neutral-d-6);\\n  --footer-wrapper-background: var(--at-black-t-5);\\n\\n  --media-wrapper-color: var(--at-neutral-d-6);\\n  --media-wrapper-background: var(--at-black-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--primary",
    "name": "at-colorset--primary",
    "settings": {
      "_cssCustom": ".at-colorset--primary {\\n  --text-color: var(--at-primary-l-5);\\n  --background: var(--at-primary);\\n  --heading-color: var(--at-primary-l-6);\\n  --accent-text-color: var(--at-secondary);\\n  \\n  --link-color:  var(--at-primary-d-5);\\n  --link-color--hover: var(--at-primary-l-6);\\n  --link-color--active: var(--at-primary-l-6);\\n  \\n  --header-wrapper-color: var(--at-primary-l-6);\\n  --header-wrapper-background: var(--at-white-t-5);\\n\\n  --body-wrapper-color: var(--at-primary-l-5);\\n  --body-wrapper-background: var(--at-white-t-5);\\n\\n  --footer-wrapper-color: var(--at-primary-l-6);\\n  --footer-wrapper-background: var(--at-white-t-5);\\n\\n  --media-wrapper-color: var(--at-primary-l-6);\\n  --media-wrapper-background: var(--at-white-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--primary-d-1",
    "name": "at-colorset--primary-d-1",
    "settings": {
      "_cssCustom": ".at-colorset--primary-d-1 {\\n  --text-color: var(--at-primary-l-5);\\n  --background: var(--at-primary-d-1);\\n  --heading-color: var(--at-primary-l-6);\\n  --accent-text-color: var(--at-secondary);\\n  \\n  --link-color:  var(--at-primary-d-5);\\n  --link-color--hover: var(--at-primary-l-6);\\n  --link-color--active: var(--at-primary-l-6);\\n  \\n    --header-wrapper-color: var(--at-primary-l-6);\\n  --header-wrapper-background: var(--at-white-t-5);\\n\\n  --body-wrapper-color: var(--at-primary-l-5);\\n  --body-wrapper-background: var(--at-white-t-5);\\n\\n  --footer-wrapper-color: var(--at-primary-l-6);\\n  --footer-wrapper-background: var(--at-white-t-5);\\n\\n  --media-wrapper-color: var(--at-primary-l-6);\\n  --media-wrapper-background: var(--at-white-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--primary-d-2",
    "name": "at-colorset--primary-d-2",
    "settings": {
      "_cssCustom": ".at-colorset--primary-d-2 {\\n  --text-color: var(--at-primary-l-5);\\n  --background: var(--at-primary-d-2);\\n  --heading-color: var(--at-primary-l-6);\\n  --accent-text-color: var(--at-secondary);\\n  \\n  --link-color:  var(--at-primary-d-5);\\n  --link-color--hover: var(--at-primary-l-6);\\n  --link-color--active: var(--at-primary-l-6);\\n  \\n    --header-wrapper-color: var(--at-primary-l-6);\\n  --header-wrapper-background: var(--at-white-t-5);\\n\\n  --body-wrapper-color: var(--at-primary-l-5);\\n  --body-wrapper-background: var(--at-white-t-5);\\n\\n  --footer-wrapper-color: var(--at-primary-l-6);\\n  --footer-wrapper-background: var(--at-white-t-5);\\n\\n  --media-wrapper-color: var(--at-primary-l-6);\\n  --media-wrapper-background: var(--at-white-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--primary-d-3",
    "name": "at-colorset--primary-d-3",
    "settings": {
      "_cssCustom": ".at-colorset--primary-d-3 {\\n  --text-color: var(--at-primary-l-5);\\n  --background: var(--at-primary-d-3);\\n  --heading-color: var(--at-primary-l-6);\\n  --accent-text-color: var(--at-secondary);\\n  \\n  --link-color:  var(--at-primary-l-4);\\n  --link-color--hover: var(--at-primary-l-6);\\n  --link-color--active: var(--at-primary-l-6);\\n  \\n    --header-wrapper-color: var(--at-primary-l-6);\\n  --header-wrapper-background: var(--at-white-t-5);\\n\\n  --body-wrapper-color: var(--at-primary-l-5);\\n  --body-wrapper-background: var(--at-white-t-5);\\n\\n  --footer-wrapper-color: var(--at-primary-l-6);\\n  --footer-wrapper-background: var(--at-white-t-5);\\n\\n  --media-wrapper-color: var(--at-primary-l-6);\\n  --media-wrapper-background: var(--at-white-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--primary-d-4",
    "name": "at-colorset--primary-d-4",
    "settings": {
      "_cssCustom": ".at-colorset--primary-d-4 {\\n  --text-color: var(--at-primary-l-5);\\n  --background: var(--at-primary-d-4);\\n  --heading-color: var(--at-primary-l-6);\\n  --accent-text-color: var(--at-secondary);\\n  \\n  --link-color:  var(--at-primary-l-4);\\n  --link-color--hover: var(--at-primary-l-6);\\n  --link-color--active: var(--at-primary-l-6);\\n  \\n    --header-wrapper-color: var(--at-primary-l-6);\\n  --header-wrapper-background: var(--at-white-t-5);\\n\\n  --body-wrapper-color: var(--at-primary-l-5);\\n  --body-wrapper-background: var(--at-white-t-5);\\n\\n  --footer-wrapper-color: var(--at-primary-l-6);\\n  --footer-wrapper-background: var(--at-white-t-5);\\n\\n  --media-wrapper-color: var(--at-primary-l-6);\\n  --media-wrapper-background: var(--at-white-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--primary-d-5",
    "name": "at-colorset--primary-d-5",
    "settings": {
      "_cssCustom": ".at-colorset--primary-d-5 {\\n  --text-color: var(--at-primary-l-5);\\n  --background: var(--at-primary-d-5);\\n  --heading-color: var(--at-primary-l-6);\\n  --accent-text-color: var(--at-secondary);\\n  \\n  --link-color:  var(--at-primary-l-4);\\n  --link-color--hover: var(--at-primary-l-6);\\n  --link-color--active: var(--at-primary-l-6);\\n  \\n    --header-wrapper-color: var(--at-primary-l-6);\\n  --header-wrapper-background: var(--at-white-t-5);\\n\\n  --body-wrapper-color: var(--at-primary-l-5);\\n  --body-wrapper-background: var(--at-white-t-5);\\n\\n  --footer-wrapper-color: var(--at-primary-l-6);\\n  --footer-wrapper-background: var(--at-white-t-5);\\n\\n  --media-wrapper-color: var(--at-primary-l-6);\\n  --media-wrapper-background: var(--at-white-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--primary-d-6",
    "name": "at-colorset--primary-d-6",
    "settings": {
      "_cssCustom": ".at-colorset--primary-d-6 {\\n  --text-color: var(--at-primary-l-5);\\n  --background: var(--at-primary-d-6);\\n  --heading-color: var(--at-primary-l-6);\\n  --accent-text-color: var(--at-secondary);\\n  \\n  --link-color:  var(--at-primary-l-4);\\n  --link-color--hover: var(--at-primary-l-6);\\n  --link-color--active: var(--at-primary-l-6);\\n  \\n    --header-wrapper-color: var(--at-primary-l-6);\\n  --header-wrapper-background: var(--at-white-t-5);\\n\\n  --body-wrapper-color: var(--at-primary-l-5);\\n  --body-wrapper-background: var(--at-white-t-5);\\n\\n  --footer-wrapper-color: var(--at-primary-l-6);\\n  --footer-wrapper-background: var(--at-white-t-5);\\n\\n  --media-wrapper-color: var(--at-primary-l-6);\\n  --media-wrapper-background: var(--at-white-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--primary-l-1",
    "name": "at-colorset--primary-l-1",
    "settings": {
      "_cssCustom": ".at-colorset--primary-l-1 {\\n  --text-color: var(--at-primary-d-5);\\n  --background: var(--at-primary-l-1);\\n  --heading-color: var(--at-primary-d-6);\\n  --accent-text-color: var(--at-secondary-d-2);\\n  \\n  --link-color:  var(--at-primary-d-2); \\n  --link-color--hover: var(--at-primary-l-6);\\n  --link-color--active: var(--at-primary-l-6);\\n  \\n  --header-wrapper-color: var(--at-primary-d-6);\\n  --header-wrapper-background: var(--at-black-t-5);\\n\\n  --body-wrapper-color: var(--at-primary-d-5);\\n  --body-wrapper-background: var(--at-black-t-5);\\n\\n  --footer-wrapper-color: var(--at-primary-d-6);\\n  --footer-wrapper-background: var(--at-black-t-5);\\n\\n  --media-wrapper-color: var(--at-primary-d-6);\\n  --media-wrapper-background: var(--at-black-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--primary-l-2",
    "name": "at-colorset--primary-l-2",
    "settings": {
      "_cssCustom": ".at-colorset--primary-l-2 {\\n  --text-color: var(--at-primary-d-5);\\n  --background: var(--at-primary-l-2);\\n  --heading-color: var(--at-primary-d-6);\\n  --accent-text-color: var(--at-secondary-d-2);\\n  \\n  --link-color:  var(--at-primary-d-2); \\n  --link-color--hover: var(--at-primary-l-6);\\n  --link-color--active: var(--at-primary-l-6);\\n  \\n  --header-wrapper-color: var(--at-primary-d-6);\\n  --header-wrapper-background: var(--at-black-t-5);\\n\\n  --body-wrapper-color: var(--at-primary-d-5);\\n  --body-wrapper-background: var(--at-black-t-5);\\n\\n  --footer-wrapper-color: var(--at-primary-d-6);\\n  --footer-wrapper-background: var(--at-black-t-5);\\n\\n  --media-wrapper-color: var(--at-primary-d-6);\\n  --media-wrapper-background: var(--at-black-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--primary-l-3",
    "name": "at-colorset--primary-l-3",
    "settings": {
      "_cssCustom": ".at-colorset--primary-l-3 {\\n  --text-color: var(--at-primary-d-5);\\n  --background: var(--at-primary-l-3);\\n  --heading-color: var(--at-primary-d-6);\\n  --accent-text-color: var(--at-secondary-d-2);\\n  \\n  --link-color:  var(--at-primary-d-2); \\n  --link-color--hover: var(--at-primary-l-6);\\n  --link-color--active: var(--at-primary-l-6);\\n  \\n  \\n  --header-wrapper-color: var(--at-primary-d-6);\\n  --header-wrapper-background: var(--at-black-t-5);\\n\\n  --body-wrapper-color: var(--at-primary-d-5);\\n  --body-wrapper-background: var(--at-black-t-5);\\n\\n  --footer-wrapper-color: var(--at-primary-d-6);\\n  --footer-wrapper-background: var(--at-black-t-5);\\n\\n  --media-wrapper-color: var(--at-primary-d-6);\\n  --media-wrapper-background: var(--at-black-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--primary-l-4",
    "name": "at-colorset--primary-l-4",
    "settings": {
      "_cssCustom": ".at-colorset--primary-l-4 {\\n  --text-color: var(--at-primary-d-5);\\n  --background: var(--at-primary-l-4);\\n  --heading-color: var(--at-primary-d-6);\\n  --accent-text-color: var(--at-secondary-d-2);\\n  \\n  --link-color:  var(--at-primary-d-2); \\n  --link-color--hover: var(--at-primary-l-6);\\n  --link-color--active: var(--at-primary-l-6);\\n  \\n  --header-wrapper-color: var(--at-primary-l-6);\\n  --header-wrapper-background: var(--at-black-t-5);\\n \\n  --header-wrapper-color: var(--at-primary-d-6);\\n  --header-wrapper-background: var(--at-black-t-5);\\n\\n  --body-wrapper-color: var(--at-primary-d-5);\\n  --body-wrapper-background: var(--at-black-t-5);\\n\\n  --footer-wrapper-color: var(--at-primary-d-6);\\n  --footer-wrapper-background: var(--at-black-t-5);\\n\\n  --media-wrapper-color: var(--at-primary-d-6);\\n  --media-wrapper-background: var(--at-black-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--primary-l-5",
    "name": "at-colorset--primary-l-5",
    "settings": {
      "_cssCustom": ".at-colorset--primary-l-5 {\\n  --text-color: var(--at-primary-d-5);\\n  --background: var(--at-primary-l-5);\\n  --heading-color: var(--at-primary-d-6);\\n  --accent-text-color: var(--at-secondary);\\n  \\n  --link-color:  var(--at-primary-d-2); \\n  --link-color--hover: var(--at-primary-d-6); \\n  --link-color--active: var(--at-primary-d-6);\\n  \\n  --header-wrapper-color: var(--at-primary-d-6);\\n  --header-wrapper-background: var(--at-black-t-5);\\n\\n  --body-wrapper-color: var(--at-primary-d-5);\\n  --body-wrapper-background: var(--at-black-t-5);\\n\\n  --footer-wrapper-color: var(--at-primary-d-6);\\n  --footer-wrapper-background: var(--at-black-t-5);\\n\\n  --media-wrapper-color: var(--at-primary-d-6);\\n  --media-wrapper-background: var(--at-black-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--primary-l-6",
    "name": "at-colorset--primary-l-6",
    "settings": {
      "_cssCustom": ".at-colorset--primary-l-6 {\\n  --text-color: var(--at-primary-d-5);\\n  --background: var(--at-primary-l-6);\\n  --heading-color: var(--at-primary-d-6);\\n  --accent-text-color: var(--at-secondary);\\n  \\n  --link-color:  var(--at-primary-d-2); \\n  --link-color--hover: var(--at-primary-d-6); \\n  --link-color--active: var(--at-primary-d-6);\\n  \\n  --header-wrapper-color: var(--at-primary-d-6);\\n  --header-wrapper-background: var(--at-black-t-5);\\n\\n  --body-wrapper-color: var(--at-primary-d-5);\\n  --body-wrapper-background: var(--at-black-t-5);\\n\\n  --footer-wrapper-color: var(--at-primary-d-6);\\n  --footer-wrapper-background: var(--at-black-t-5);\\n\\n  --media-wrapper-color: var(--at-primary-d-6);\\n  --media-wrapper-background: var(--at-black-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--secondary",
    "name": "at-colorset--secondary",
    "settings": {
      "_cssCustom": ".at-colorset--secondary {\\n  --text-color: var(--at-secondary-l-5);\\n  --background: var(--at-secondary);\\n  --heading-color: var(--at-secondary-l-6);\\n  --accent-text-color: var(--at-primary);\\n  \\n  --link-color:  var(--at-secondary-d-5);\\n  --link-color--hover: var(--at-secondary-l-6);\\n  --link-color--active: var(--at-secondary-l-6);\\n  \\n  --header-wrapper-color: var(--at-secondary-l-6);\\n  --header-wrapper-background: var(--at-white-t-5);\\n\\n  --body-wrapper-color: var(--at-secondary-l-5);\\n  --body-wrapper-background: var(--at-white-t-5);\\n\\n  --footer-wrapper-color: var(--at-secondary-l-6);\\n  --footer-wrapper-background: var(--at-white-t-5);\\n\\n  --media-wrapper-color: var(--at-secondary-l-6);\\n  --media-wrapper-background: var(--at-white-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--secondary-d-1",
    "name": "at-colorset--secondary-d-1",
    "settings": {
      "_cssCustom": ".at-colorset--secondary-d-1 {\\n  --text-color: var(--at-secondary-l-5);\\n  --background: var(--at-secondary-d-1);\\n  --heading-color: var(--at-secondary-l-6);\\n  --accent-text-color: var(--at-primary);\\n  \\n  --link-color:  var(--at-secondary-d-5);\\n  --link-color--hover: var(--at-secondary-l-6);\\n  --link-color--active: var(--at-secondary-l-6);\\n  \\n  --header-wrapper-color: var(--at-secondary-l-6);\\n  --header-wrapper-background: var(--at-white-t-5);\\n\\n  --body-wrapper-color: var(--at-secondary-l-5);\\n  --body-wrapper-background: var(--at-white-t-5);\\n\\n  --footer-wrapper-color: var(--at-secondary-l-6);\\n  --footer-wrapper-background: var(--at-white-t-5);\\n\\n  --media-wrapper-color: var(--at-secondary-l-6);\\n  --media-wrapper-background: var(--at-white-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--secondary-d-2",
    "name": "at-colorset--secondary-d-2",
    "settings": {
      "_cssCustom": ".at-colorset--secondary-d-2 {\\n  --text-color: var(--at-secondary-l-5);\\n  --background: var(--at-secondary-d-2);\\n  --heading-color: var(--at-secondary-l-6);\\n  --accent-text-color: var(--at-primary);\\n  \\n  --link-color:  var(--at-secondary-d-5);\\n  --link-color--hover: var(--at-secondary-l-6);\\n  --link-color--active: var(--at-secondary-l-6);\\n  \\n  --header-wrapper-color: var(--at-secondary-l-6);\\n  --header-wrapper-background: var(--at-white-t-5);\\n\\n  --body-wrapper-color: var(--at-secondary-l-5);\\n  --body-wrapper-background: var(--at-white-t-5);\\n\\n  --footer-wrapper-color: var(--at-secondary-l-6);\\n  --footer-wrapper-background: var(--at-white-t-5);\\n\\n  --media-wrapper-color: var(--at-secondary-l-6);\\n  --media-wrapper-background: var(--at-white-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--secondary-d-3",
    "name": "at-colorset--secondary-d-3",
    "settings": {
      "_cssCustom": ".at-colorset--secondary-d-3 {\\n  --text-color: var(--at-secondary-l-5);\\n  --background: var(--at-secondary-d-3);\\n  --heading-color: var(--at-secondary-l-6);\\n  --accent-text-color: var(--at-primary);\\n  \\n  --link-color:  var(--at-secondary-l-4);\\n  --link-color--hover: var(--at-secondary-l-6);\\n  --link-color--active: var(--at-secondary-l-6);\\n  \\n  --header-wrapper-color: var(--at-secondary-l-6);\\n  --header-wrapper-background: var(--at-white-t-5);\\n\\n  --body-wrapper-color: var(--at-secondary-l-5);\\n  --body-wrapper-background: var(--at-white-t-5);\\n\\n  --footer-wrapper-color: var(--at-secondary-l-6);\\n  --footer-wrapper-background: var(--at-white-t-5);\\n\\n  --media-wrapper-color: var(--at-secondary-l-6);\\n  --media-wrapper-background: var(--at-white-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--secondary-d-4",
    "name": "at-colorset--secondary-d-4",
    "settings": {
      "_cssCustom": ".at-colorset--secondary-d-4 {\\n  --text-color: var(--at-secondary-l-5);\\n  --background: var(--at-secondary-d-4);\\n  --heading-color: var(--at-secondary-l-6);\\n  --accent-text-color: var(--at-primary);\\n    \\n  --link-color:  var(--at-secondary-l-4);\\n  --link-color--hover: var(--at-secondary-l-6);\\n  --link-color--active: var(--at-secondary-l-6);\\n  \\n  --header-wrapper-color: var(--at-secondary-l-6);\\n  --header-wrapper-background: var(--at-white-t-5);\\n\\n  --body-wrapper-color: var(--at-secondary-l-5);\\n  --body-wrapper-background: var(--at-white-t-5);\\n\\n  --footer-wrapper-color: var(--at-secondary-l-6);\\n  --footer-wrapper-background: var(--at-white-t-5);\\n\\n  --media-wrapper-color: var(--at-secondary-l-6);\\n  --media-wrapper-background: var(--at-white-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--secondary-d-5",
    "name": "at-colorset--secondary-d-5",
    "settings": {
      "_cssCustom": ".at-colorset--secondary-d-5 {\\n  --text-color: var(--at-secondary-l-5);\\n  --background: var(--at-secondary-d-5);\\n  --heading-color: var(--at-secondary-l-6);\\n  --accent-text-color: var(--at-primary);\\n    \\n  --link-color:  var(--at-secondary-l-4);\\n  --link-color--hover: var(--at-secondary-l-6);\\n  --link-color--active: var(--at-secondary-l-6);\\n  \\n  --header-wrapper-color: var(--at-secondary-l-6);\\n  --header-wrapper-background: var(--at-white-t-5);\\n\\n  --body-wrapper-color: var(--at-secondary-l-5);\\n  --body-wrapper-background: var(--at-white-t-5);\\n\\n  --footer-wrapper-color: var(--at-secondary-l-6);\\n  --footer-wrapper-background: var(--at-white-t-5);\\n\\n  --media-wrapper-color: var(--at-secondary-l-6);\\n  --media-wrapper-background: var(--at-white-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--secondary-d-6",
    "name": "at-colorset--secondary-d-6",
    "settings": {
      "_cssCustom": ".at-colorset--secondary-d-6 {\\n  --text-color: var(--at-secondary-l-5);\\n  --background: var(--at-secondary-d-6);\\n  --heading-color: var(--at-secondary-l-6);\\n  --accent-text-color: var(--at-primary);\\n    \\n  --link-color:  var(--at-secondary-l-4);\\n  --link-color--hover: var(--at-secondary-l-6);\\n  --link-color--active: var(--at-secondary-l-6);\\n  \\n  --header-wrapper-color: var(--at-secondary-l-6);\\n  --header-wrapper-background: var(--at-white-t-5);\\n\\n  --body-wrapper-color: var(--at-secondary-l-5);\\n  --body-wrapper-background: var(--at-white-t-5);\\n\\n  --footer-wrapper-color: var(--at-secondary-l-6);\\n  --footer-wrapper-background: var(--at-white-t-5);\\n\\n  --media-wrapper-color: var(--at-secondary-l-6);\\n  --media-wrapper-background: var(--at-white-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--secondary-l-1",
    "name": "at-colorset--secondary-l-1",
    "settings": {
      "_cssCustom": ".at-colorset--secondary-l-1 {\\n  --text-color: var(--at-secondary-d-5);\\n  --background: var(--at-secondary-l-1);\\n  --heading-color: var(--at-secondary-d-6);\\n  --accent-text-color: var(--at-primary-d-2);\\n    \\n  --link-color:  var(--at-secondary-d-4);\\n  --link-color--hover: var(--at-secondary-d-6);\\n  --link-color--active: var(--at-secondary-l-6);\\n  \\n  --header-wrapper-color: var(--at-secondary-d-6);\\n  --header-wrapper-background: var(--at-black-t-5);\\n\\n  --body-wrapper-color: var(--at-secondary-d-5);\\n  --body-wrapper-background: var(--at-black-t-5);\\n\\n  --footer-wrapper-color: var(--at-secondary-d-6);\\n  --footer-wrapper-background: var(--at-black-t-5);\\n\\n  --media-wrapper-color: var(--at-secondary-d-6);\\n  --media-wrapper-background: var(--at-black-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--secondary-l-2",
    "name": "at-colorset--secondary-l-2",
    "settings": {
      "_cssCustom": ".at-colorset--secondary-l-2 {\\n  --text-color: var(--at-secondary-d-5);\\n  --background: var(--at-secondary-l-2);\\n  --heading-color: var(--at-secondary-d-6);\\n  --accent-text-color: var(--at-primary-d-2);\\n    \\n  --link-color:  var(--at-secondary-d-4);\\n  --link-color--hover: var(--at-secondary-d-6);\\n  --link-color--active: var(--at-secondary-l-6);\\n  \\n  --header-wrapper-color: var(--at-secondary-d-6);\\n  --header-wrapper-background: var(--at-black-t-5);\\n\\n  --body-wrapper-color: var(--at-secondary-d-5);\\n  --body-wrapper-background: var(--at-black-t-5);\\n\\n  --footer-wrapper-color: var(--at-secondary-d-6);\\n  --footer-wrapper-background: var(--at-black-t-5);\\n\\n  --media-wrapper-color: var(--at-secondary-d-6);\\n  --media-wrapper-background: var(--at-black-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--secondary-l-3",
    "name": "at-colorset--secondary-l-3",
    "settings": {
      "_cssCustom": ".at-colorset--secondary-l-3 {\\n  --text-color: var(--at-secondary-d-5);\\n  --background: var(--at-secondary-l-3);\\n  --heading-color: var(--at-secondary-d-6);\\n  --accent-text-color: var(--at-primary-d-2);\\n  \\n  --link-color:  var(--at-secondary-d-1);\\n  --link-color--hover: var(--at-secondary-d-6);\\n  --link-color--active: var(--at-secondary-l-6);\\n  \\n  --header-wrapper-color: var(--at-secondary-d-6);\\n  --header-wrapper-background: var(--at-black-t-5);\\n\\n  --body-wrapper-color: var(--at-secondary-d-5);\\n  --body-wrapper-background: var(--at-black-t-5);\\n\\n  --footer-wrapper-color: var(--at-secondary-d-6);\\n  --footer-wrapper-background: var(--at-black-t-5);\\n\\n  --media-wrapper-color: var(--at-secondary-d-6);\\n  --media-wrapper-background: var(--at-black-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--secondary-l-4",
    "name": "at-colorset--secondary-l-4",
    "settings": {
      "_cssCustom": ".at-colorset--secondary-l-4 {\\n  --text-color: var(--at-secondary-d-5);\\n  --background: var(--at-secondary-l-4);\\n  --heading-color: var(--at-secondary-d-6);\\n  --accent-text-color: var(--at-primary-d-2);\\n  \\n  --link-color:  var(--at-secondary-d-1);\\n  --link-color--hover: var(--at-secondary-d-6);\\n  --link-color--active: var(--at-secondary-l-6);\\n  \\n  --header-wrapper-color: var(--at-secondary-d-6);\\n  --header-wrapper-background: var(--at-black-t-5);\\n\\n  --body-wrapper-color: var(--at-secondary-d-5);\\n  --body-wrapper-background: var(--at-black-t-5);\\n\\n  --footer-wrapper-color: var(--at-secondary-d-6);\\n  --footer-wrapper-background: var(--at-black-t-5);\\n\\n  --media-wrapper-color: var(--at-secondary-d-6);\\n  --media-wrapper-background: var(--at-black-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--secondary-l-5",
    "name": "at-colorset--secondary-l-5",
    "settings": {
      "_cssCustom": ".at-colorset--secondary-l-5 {\\n  --text-color: var(--at-secondary-d-5);\\n  --background: var(--at-secondary-l-5);\\n  --heading-color: var(--at-secondary-d-6);\\n  --accent-text-color: var(--at-primary-d-2);\\n  \\n  --link-color:  var(--at-secondary-d-1);\\n  --link-color--hover: var(--at-secondary-d-6);\\n  --link-color--active: var(--at-secondary-l-6);\\n  \\n  --header-wrapper-color: var(--at-secondary-d-6);\\n  --header-wrapper-background: var(--at-black-t-5);\\n\\n  --body-wrapper-color: var(--at-secondary-d-5);\\n  --body-wrapper-background: var(--at-black-t-5);\\n\\n  --footer-wrapper-color: var(--at-secondary-d-6);\\n  --footer-wrapper-background: var(--at-black-t-5);\\n\\n  --media-wrapper-color: var(--at-secondary-d-6);\\n  --media-wrapper-background: var(--at-black-t-5);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_colorset--secondary-l-6",
    "name": "at-colorset--secondary-l-6",
    "settings": {
      "_cssCustom": ".at-colorset--secondary-l-6 {\\n    --text-color: var(--at-secondary-d-5);\\n  --background: var(--at-secondary-l-6);\\n  --heading-color: var(--at-secondary-d-6);\\n  --accent-text-color: var(--at-primary-d-2);\\n  \\n  --link-color:  var(--at-secondary-d-1);\\n  --link-color--hover: var(--at-secondary-d-6);\\n  --link-color--active: var(--at-secondary-l-6);\\n  \\n  --header-wrapper-color: var(--at-secondary-d-6);\\n  --header-wrapper-background: var(--at-black-t-5);\\n\\n  --body-wrapper-color: var(--at-secondary-d-5);\\n  --body-wrapper-background: var(--at-black-t-5);\\n\\n  --footer-wrapper-color: var(--at-secondary-d-6);\\n  --footer-wrapper-background: var(--at-black-t-5);\\n\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_colorset"
  },
  {
    "id": "at_focus--dark",
    "name": "at-focus--dark",
    "settings": {
      "_cssCustom": ".at-focus--dark{\n  --at-focus-outline-color: var(--at-black);\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_focus"
  },
  {
    "id": "at_focus--light",
    "name": "at-focus--light",
    "settings": {
      "_cssCustom": ".at-focus--light{\n  --at-focus-outline-color: var(--at-white);\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_focus"
  },
  {
    "id": "at_focus--primary",
    "name": "at-focus--primary",
    "settings": {
      "_cssCustom": ".at-focus--primary{\n  --at-focus-outline-color: var(--at-primary);\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_focus"
  },
  {
    "id": "at_focus--secondary",
    "name": "at-focus--secondary",
    "settings": {
      "_cssCustom": ".at-focus--secondary{\n  --at-focus-outline-color: var(--at-secondary);\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_focus"
  },
  {
    "id": "at_focus--neutral",
    "name": "at-focus--neutral",
    "settings": {
      "_cssCustom": ".at-focus--neutral{\n  --at-focus-outline-color: var(--at-neutral);\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_focus"
  },
  {
    "id": "at_focus-here",
    "name": "at-focus-here",
    "settings": {
      "_cssCustom": "body.bricks-is-frontend .at-focus-here :focus-visible{\n\toutline: none !important;\n}\n\n.at-focus-here:has(:focus-visible){\n  outline-color: var(--at-focus-outline-color);\n  outline-width: var(--at-focus-outline-width);\n  outline-offset: vvar(--at-focus-outline-offset);\n  outline-style: solid;\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_focus"
  },
  {
    "id": "at_line-clamp",
    "name": "at-line-clamp",
    "settings": {
      "_cssCustom": "/*\\nLine Clamp \\n*/\\n[class*=line-clamp--]{\\n  line-clamp: var(--lines);\\n  /* Native support in some modern browsers */\\n  overflow: hidden;\\n  display: -webkit-box;\\n  -webkit-box-orient: vertical;\\n  -webkit-line-clamp: var(--lines);\\n}\\n\\n/* fix IOS issue with non inline elements */\\n[class*=line-clamp--]>p {\\n  display: inline;\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_line-clamp"
  },
  {
    "id": "at_line-clamp--1",
    "name": "at-line-clamp--1",
    "settings": {
      "_cssCustom": ".at-line-clamp--1 {\n  --lines: 1;\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_line-clamp"
  },
  {
    "id": "at_line-clamp--2",
    "name": "at-line-clamp--2",
    "settings": {
      "_cssCustom": ".at-line-clamp--2 {\n  --lines: 2;\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_line-clamp"
  },
  {
    "id": "at_line-clamp--3",
    "name": "at-line-clamp--3",
    "settings": {
      "_cssCustom": ".at-line-clamp--3 {\n  --lines: 3;\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_line-clamp"
  },
  {
    "id": "at_line-clamp--4",
    "name": "at-line-clamp--4",
    "settings": {
      "_cssCustom": ".at-line-clamp--4 {\n  --lines: 4;\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_line-clamp"
  },
  {
    "id": "at_line-clamp--5",
    "name": "at-line-clamp--5",
    "settings": {
      "_cssCustom": ".at-line-clamp--5 {\n  --lines: 5;\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_line-clamp"
  },
  {
    "id": "at_mark",
    "name": "at-mark",
    "settings": {
      "_cssCustom": "mark[class*=mark--],\\n[class*=mark--] mark {\\n  --_color: var(--color, var(--at-mark-color));\\n  --_line-height: var(--line-height, var(--at-mark-line-height));\\n  --_padding: var(--padding, var(--at-mark-padding));\\n  --_text-shadow: var(--text-shadow, var(--at-mark-text-shadow));\\n  --_font-size: var(--font-size, var(--at-mark-font-size));\\n  --_font-weight: var(--font-weight, var(--at-mark-font-weight));\\n  --_text-transform: var(--text-transform, var(--at-mark-text-transform));\\n  --_letter-spacing: var(--letter-spacing, var(--at-mark-letter-spacing));\\n  --_transform: var(--transform, var(--at-mark-transform));\\n\\n  --_background: var(--background, var(--at-mark-background));\\n  --_box-shadow: var(--background, var(--at-mark-shadow));\\n  --_border: var(--border, var(--at-mark-border-width) solid var(--at-mark-border-color));\\n  --_border-radius: var(--border-radius, var(--at-mark-border-radius));\\n  --_background-transform: var(--background-transform, var(--at-mark-background-transform));\\n  --_inset-block: var(--inset-block, var(--at-mark-inset-block));\\n  --_inset-inline: var(--inset-inline, var(--at-mark-inset-inline));\\n}\\n\\nmark.at-mark,\\n.at-mark mark {\\n  display: inline-flex;\\n  position: relative;\\n  isolation: isolate;\\n  color: var(--_color);\\n  line-height: var(--_line-height);\\n  background: transparent;\\n  padding: var(--_padding);\\n  text-shadow: var(--_text-shadow);\\n  font-size: var(--_font-size);\\n  font-weight: var(--at-mark-font-weight);\\n  text-transform: var(--_text-transform);\\n  letter-spacing: var(--_letter-spacing);\\n  transform: var(--_transform);\\n}\\n\\nmark.at-mark::before,\\n.at-mark mark::before {\\n  position: absolute;\\n  content: \"\";\\n  inset-block: var(--_inset-block);\\n  inset-inline: var(--_inset-inline);\\n  background: var(--_background);\\n  box-shadow: var(--_box-shadow);\\n  border: var(--_border);\\n  border-radius: var(--_border-radius);\\n  z-index: -1;\\n  transform: var(--_background-transform);\\n}\\n\\n/* Marke modifiers here, so they can be used in HTML whthout Bricks outputting the rules */\\n.at-mark--primary {\\n    --color: var(--at-primary-l-5);\\n    --background: var(--at-primary);\\n}\\n\\n.at-mark--secondary {\\n    --color: var(--at-secondary-l-5);\\n    --background: var(--at-secondary);\\n}\\n.at-mark--dark {\\n    --color: var(--at-neutral-l-5);\\n    --background: var(--at-neutral-d-5);\\n}\\n.at-mark--light {\\n    --color: var(--at-neutral-d-5);\\n    --background: var(--at-neutral-l-6);\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_marks"
  },
  {
    "id": "at_mark--dark",
    "name": "at-mark--dark",
    "settings": {
      "_cssCustom": "/* See <at-mark> Global Class */",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_marks"
  },
  {
    "id": "at_mark--light",
    "name": "at-mark--light",
    "settings": {
      "_cssCustom": "/* See <at-mark> Global Class */",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_marks"
  },
  {
    "id": "at_mark--primary",
    "name": "at-mark--primary",
    "settings": {
      "_cssCustom": "/* See <at-mark> Global Class */",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_marks"
  },
  {
    "id": "at_mark--secondary",
    "name": "at-mark--secondary",
    "settings": {
      "_cssCustom": "/* See <at-mark> Global Class */",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_marks"
  },
  {
    "id": "at_ul-padding--0",
    "name": "at-ul-padding--0",
    "settings": {
      "_cssCustom": ".at-ul-padding--0 ul{\n  padding: 0;\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_ul"
  },
  {
    "id": "at_ul-padding--s",
    "name": "at-ul-padding--s",
    "settings": {
      "_cssCustom": ".at-ul-padding--s ul{\n  padding-inline-start: var(--at-space--s);\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_ul"
  },
  {
    "id": "at_ul-svg-icon",
    "name": "at-ul-svg-icon",
    "settings": {
      "_cssCustom": "/* \\nReplace ULs in text elements with SVG Icons\\nNote: This uses a Grid layout. It will not work for item content with additional html elements.\\n*/\\n[class*=ul-svg-icon--] {\\n  --_item-gap: var(--item-gap, 0.25em);\\n  --_font-size: var(--font-size, var(--at-text--s));\\n  --_icon-gap: var(--gap, 0.5em);\\n  --_image-mask: var(--image-mask, var(--at-svg--arrow));\\n  --_icon-color: var(--icon-color, var(--at-primary));\\n  --_icon-offset: var(--icon-offset, 0.3em);\\n  --_icon-size: var(--icon-size, 1.2em);\\n}\\n\\n[class*=ul-svg-icon--] ul {\\n  list-style-type: none;\\n  display: flex;\\n  flex-direction: column;\\n  gap: var(--_item-gap);\\n  font-size: var(--_font-size);\\n}\\n\\n[class*=ul-svg-icon--] ul > li {\\n  display: grid;\\n  grid-template-columns: auto 1fr;\\n  gap: var(--_icon-gap);\\n}\\n\\n[class*=ul-svg-icon--] ul > li::before {\\n  content: \"\";\\n  width: var(--_icon-size);\\n  height: var(--_icon-size);\\n  background-color: var(--_icon-color);\\n  margin-block-start: var(--_icon-offset);\\n  object-fit: cover;\\n  mask-image: var(--_image-mask);\\n  -webkit-mask-size: cover;\\n  mask-size: cover;\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_ul"
  },
  {
    "id": "at_ul-svg-icon--black",
    "name": "at-ul-svg-icon--black",
    "settings": {
      "_cssCustom": ".at-ul-svg-icon--black {\n  --icon-color: var(--at-black);\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_ul"
  },
  {
    "id": "at_ul-svg-icon--check",
    "name": "at-ul-svg-icon--check",
    "settings": {
      "_cssCustom": ".at-ul-svg-icon--check{\n\t--image-mask: var(--at-svg--check);\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_ul"
  },
  {
    "id": "at_ul-svg-icon--cross",
    "name": "at-ul-svg-icon--cross",
    "settings": {
      "_cssCustom": ".at-ul-svg-icon--cross{\n  \t--image-mask: var(--at-svg--cross);\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_ul"
  },
  {
    "id": "at_ul-svg-icon--dark",
    "name": "at-ul-svg-icon--dark",
    "settings": {
      "_cssCustom": ".at-ul-svg-icon--dark {\n  --icon-color: var(--at-neutral-d-5);\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_ul"
  },
  {
    "id": "at_ul-svg-icon--delete",
    "name": "at-ul-svg-icon--delete",
    "settings": {
      "_cssCustom": ".at-ul-svg-icon--delete {\n  --image-mask: var(--at-svg--delete);\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_ul"
  },
  {
    "id": "at_ul-svg-icon--heart",
    "name": "at-ul-svg-icon--heart",
    "settings": {
      "_cssCustom": ".at-ul-svg-icon--heart{\n  \t--image-mask: var(--at-svg--heart);\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_ul"
  },
  {
    "id": "at_ul-svg-icon--light",
    "name": "at-ul-svg-icon--light",
    "settings": {
      "_cssCustom": ".at-ul-svg-icon--light {\n  --icon-color: var(--at-neutral-l-5);\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_ul"
  },
  {
    "id": "at_ul-svg-icon--secondary",
    "name": "at-ul-svg-icon--secondary",
    "settings": {
      "_cssCustom": ".at-ul-svg-icon--secondary {\n  --icon-color: var(--at-secondary);\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_ul"
  },
  {
    "id": "at_clickable-box",
    "name": "at-clickable-box",
    "settings": {
      "_cssCustom": ".at-clickable-box {\n\tposition: relative;\n}\n\n.at-clickable-box a{\n  position: static;\n  z-index:999;\n}\n\n.bricks-is-frontend .at-clickable-box a::before{\n  content: \"\";\n  position: absolute;\n  inset: 0;\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_accessibility"
  },
  {
    "id": "at_visually-hidden",
    "name": "at-visually-hidden",
    "settings": {
      "_cssCustom": ".at-visually-hidden {\\n\\tclip: rect(0 0 0 0);\\n  clip-path: inset(50%);\\n  height: 1px;\\n  overflow: hidden;\\n  position: absolute;\\n  white-space: nowrap;\\n  width: 1px;\\n}",
      "at_framework": true,
      "at_version": "1.0.0"
    },
    "category": "at_accessibility"
  },
  {
    "id": "at_bricks-form-inline",
    "name": "at-bricks-form-inline",
    "settings": {
        "_cssCustom": ".at-bricks-form-inline{\n  --_gap: var(--gap, var(--at-space--xs));\n  --_border-radius: var(--border-radius, var(--at-form-input-border-radius));\n  --_input-height: var(--input-height, 60px);\n  --_input-padding-block: var(--input-padding-block, 0); /* not really needed. inputes will center to height */\n  --_input-padding-inline: var(--input-padding-inline, var(--at-form-input-padding-inline));\n  --_input-min-width: var(--input-min-width, min(200px, 100%));\n}\n\n.at-bricks-form-inline{\n  display: flex;\n  gap: var(--_gap);\n}\n\n.at-bricks-form-inline.brxe-form input:not([type=checkbox],[type=radio]), \n.at-bricks-form-inline.brxe-form textarea,\n.at-bricks-form-inline.brxe-form select,\n.at-bricks-form-inline.brxe-form button{\n  border-radius: var(--_border-radius);\n  padding-block: var( --_input-padding-block);\n  padding-inline: var(--_input-padding-inline);\n}\n\n.at-bricks-form-inline > div:not(.submit-button-wrapper,:has(.options-wrapper) ),\n.at-bricks-form-inline > * :is(input, select, textarea){\n  flex: 999 0;\n  align-content: center;\n}\n\n.at-bricks-form-inline > div:not(.submit-button-wrapper, .message, :has(.options-wrapper) ),\n.at-bricks-form-inline > * :is(input:not([type=checkbox],[type=radio]), select, textarea)\n{\n  min-height: var(--_input-height);\n  max-height: var(--_input-height);\n  min-width: var(--_input-min-width);\n}\n\n.at-bricks-form-inline .options-wrapper{\n  display: flex;\n  min-height: var(--_input-height);\n  max-height: var(--_input-height);\n  align-items: center;\n}\n\n.at-bricks-form-inline *:has(.options-wrapper){\n  width: auto;\n  height: 100%;\n}\n\n.at-bricks-form-inline > .submit-button-wrapper{\n  flex-grow: 1;\n  min-height: var(--_input-height);\n  max-height: var(--_input-height);\n}\n.at-bricks-form-inline .form-group{\n  padding: 0;\n}\n.at-bricks-form-inline .form-group button{\n  flex-grow: 1;\n}\n\n.at-bricks-form-inline .message {\n  min-width: 100%;\n}",
        "at_framework": true,
        "at_version": "1.1.0"
    },
    "category": "at_forms"
    },
  {
    "id": "at_bricks-form-styled",
    "name": "at-bricks-form-styled",
    "settings": {
      "_cssCustom": "/* This class styles teh Bricks Form with options\nNot found in Theme settings\n*/\n\n.at-bricks-form-styled {\n  --_icon-gap: var(--icon-gap, var(--at-space--xs));\n\n  --_icon-background: var(--icon-background, var(--at-form-option-on-color));\n  --_icon-background--unchecked: var(--icon-background--checked, var(--at-form-option-off-color));\n\n  --_icon-size: var(--icon-size, 1.2em);\n  --_icon-offset: var(--icon-offset, .3em);\n\n  --_checkbox-svg: var(--checkbox-svg, var(--at-svg-cancel-solid));\n  --_checkbox-checked-svg: var(--checkbox-checked-svg, var(--at-svg-check-solid));\n\n  --_radio-svg: var(--radio-svg, var(--at-svg-radio-unselected));\n  --_radio-checked-svg: var(--radio-checked-svg, var(--at-svg-radio-selected));\n\n  --_valid-color: var(--valid-color, var(--at-form-valid-color));\n  --_invalid-color: var(--invalid-color, var(--at-form-invalid-color));\n}\n\n/* styles here because options are missing in UI settings */\n.brxe-form option{\n  color: var(--at-form-option-color);\n  background: var(--at-form-option-background);\n}\n\n/* Normally the inputs have a border to indicate focus, so we may want to disable the outline */\nbody.bricks-is-frontend .brxe-form :not([type=radio], [type=checkbox]):focus-visible{\n  outline-width: var(--at-form-focus-outline-width);\n}\n\n.at-bricks-form-styled .bricks-button {\n  /*Input radius in theme overrides button radius. Reset it here*/\n  border-radius: var(--at-btn-border-radius);\n}\n\n/* Put some radius on our option groups for the validity indicators */\n.at-bricks-form-styled .options-wrapper {\n  border-radius: var(--at-form-input-border-radius);\n  border-width: var(--at-form-input-border-width);\n  border-style: solid;\n  border-color: transparent;\n}\n\n/* CHECKBOX AND RADIO */\n.at-bricks-form-styled input:is([type=checkbox], [type=radio]) {\n  position: absolute;\n  width: 1px;\n  height: 1px;\n  padding: 0;\n  margin: -1px;\n  overflow: hidden;\n  clip: rect(0, 0, 0, 0);\n  white-space: nowrap;\n  border: 0;\n}\n\n.at-bricks-form-styled input:is([type=checkbox], [type=radio])+label {\n  display: flex;\n  column-gap: var(--_icon-gap);\n}\n\n.at-bricks-form-styled input:is([type=checkbox], [type=radio])+label::before {\n  content: \"\";\n  margin-block-start: var(--_icon-offset);\n  background: var(--_icon-background--unchecked);\n  width: var(--_icon-size);\n  height: var(--_icon-size);\n  display: inline-flex;\n\n  /* Apply the SVG as a mask */\n  mask-repeat: no-repeat;\n  mask-position: center;\n  mask-size: contain;\n\n  /* For Safari */\n  -webkit-mask-repeat: no-repeat;\n  -webkit-mask-position: center;\n  -webkit-mask-size: contain;\n}\n\n/* CHECKBOX */\n.at-bricks-form-styled input:is([type=checkbox])+label::before {\n  mask-image: var(--_checkbox-svg);\n  -webkit-mask-image: var(--_checkbox-svg);\n}\n\n.at-bricks-form-styled input:is([type=checkbox]):checked+label::before {\n  background: var(--_icon-background);\n  mask-image: var(--_checkbox-svg);\n  -webkit-mask-image: var(--_checkbox-checked-svg);\n}\n\n/* RADIO */\n.at-bricks-form-styled input:is([type=radio])+label::before {\n  mask-image: var(--_radio-svg);\n  -webkit-mask-image: var(--_radio-svg);\n}\n\n.at-bricks-form-styled input:is([type=radio]):checked+label::before {\n  background: var(--_icon-background);\n  mask-image: var(--_radio-checked-svg);\n  -webkit-mask-image: var(--_radio-checked-svg);\n}\n\n/* Focus on label */\n.at-bricks-form-styled input:is([type=checkbox], [type=radio]):focus-visible+label {\n  outline-color: var(--at-focus-outline-color);\n  outline-offset: var(--at-focus-outline-offset);\n  outline-width: var(--at-focus-outline-width);\n  outline-style: solid;\n  border-radius: var(--at-form-input-border-radius);\n}\n\n/* Valid / Invalid indicator - comment out if not wanted */\n\n/* validity indicators for checkbox and radio */\n\n\n.at-bricks-form-styled.brxe-form input:user-valid,\n.at-bricks-form-styled.brxe-form select:user-valid,\n.at-bricks-form-styled.brxe-form textarea:user-valid,\n.at-bricks-form-styled.brxe-form .options-wrapper:has(input[type=checkbox]:user-valid, input[type=radio]:user-valid){\n  border-color: var(--_valid-color);\n}\n\n.at-bricks-form-styled.brxe-form input:user-invalid,\n.at-bricks-form-styled.brxe-form select:user-invalid,\n.at-bricks-form-styled.brxe-form textarea:user-invalid,\n.at-bricks-form-styled.brxe-form .options-wrapper:has(input[type=checkbox]:user-invalid, input[type=radio]:user-invalid){\n  border-color: var(--_invalid-color);\n}\n",
      "at_framework": true,
      "at_version": "1.1.0"
    },
    "category": "at_forms"
  },
  {
    "id": "at_placeholder",
    "name": "at-placeholder",
    "settings": {
        "_cssCustom": ".at-placeholder {\\n\\t--_background-color: var(--background-color, var(--at-neutral-t-5));\\n  --_border: var( --border, 2px dashed var(--at-neutral-t-4) );\\n  --_border-radius: var(--border-radius, var(--at-radius--s));\\n  --_padding: var( --padding, var(--at-space--s));\\n  --_min-height: var(--min-height, 90px);\\n  \\n  --_label-background-color: var(--label-background-color, var(--at-neutral-t-3));\\n  --_label-color: var(--label-color, var(--at-white));\\n  --_label-padding: var(--label-padding, 4px);\\n  --_label-border-radius:  var(--label-padding, 3px);\\n  \\n  --_icon-size: var(--icon-size, 2rem);\\n  --_icon-opacity: var(--icon-opacity, 0.5) ;\\n  --_icon-svg: var(--icon-svg, url(\'data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" color=\"%23000000\" fill=\"none\"><circle opacity=\"0.4\" cx=\"8.5\" cy=\"8.5\" r=\"1.5\" fill=\"currentColor\" /><path opacity=\"0.4\" d=\"M9.99997 21H14C17.7712 21 19.6568 21 20.8284 19.8284C21.4033 19.2535 21.6961 18.5066 21.8452 17.4493L16.348 11.3797C16.1262 11.1377 15.8131 11 15.4849 11C15.1744 11 14.8766 11.1234 14.6571 11.3429L9.99997 16L7.83925 13.8393C7.62201 13.622 7.32738 13.5 7.02016 13.5C6.68928 13.5 6.3742 13.6415 6.15438 13.8888L2.26257 18.0478C2.43227 18.7986 2.71164 19.3685 3.17154 19.8284C4.34312 21 6.22873 21 9.99997 21Z\" fill=\"currentColor\" /><path d=\"M14 3H10C6.22876 3 4.34315 3 3.17157 4.17157C2 5.34315 2 7.22876 2 11V13C2 16.7712 2 18.6569 3.17157 19.8284C4.34315 21 6.22876 21 10 21H14C17.7712 21 19.6569 21 20.8284 19.8284C22 18.6569 22 16.7712 22 13V11C22 7.22876 22 5.34315 20.8284 4.17157C19.6569 3 17.7712 3 14 3Z\" stroke=\"currentColor\" stroke-width=\"1.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\" /><circle cx=\"8.5\" cy=\"8.5\" r=\"1.5\" stroke=\"currentColor\" stroke-width=\"1.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\" /><path d=\"M21.5 17L16.348 11.3797C16.1263 11.1377 15.8131 11 15.485 11C15.1744 11 14.8766 11.1234 14.6571 11.3429L10 16L7.83928 13.8393C7.62204 13.622 7.32741 13.5 7.02019 13.5C6.68931 13.5 6.37423 13.6415 6.15441 13.8888L2.5 18\" stroke=\"currentColor\" stroke-width=\"1.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\" /></svg>\'));\\n}\\n\\n.at-placeholder {\\n\\tbackground-color: var(--_background-color);\\n  border: var(--_border);\\n  border-radius: var(--_border-radius);\\n  padding: var(--_padding);\\n  display: flex;\\n  align-items: center;\\n  justify-content: center;\\n  min-height: var(--_min-height);\\n}\\n\\n.at-placeholder::before{\\n  width: var(--_icon-size);\\n  height: var(--_icon-size);\\n  opacity:var(--_icon-opacity);\\n  \\n  content: var(--_icon-svg);\\n}\\n\\n.at-placeholder__label{\\n  line-height: 1.1;\\n  padding: var(--_label-padding);\\n  background-color: var(--_label-background-color);\\n  color: var( --_label-color);\\n  border-radius: var(--_label-border-radius);\\n}",
        "at_framework": true,
        "at_version": "1.1.0"        
    },
    "category": "at_placeholder"
  },
  {
    "id": "at_placeholder--l",
    "name": "at-placeholder--l",
    "settings": {
      "_cssCustom": ".at-placeholder--l {\\n\\t--min-height: 200px;\\n}",
        "at_framework": true,
        "at_version": "1.1.0"
    },
    "category": "at_placeholder"
  },
  {
    "id": "at_placeholder--xl",
    "name": "at-placeholder--xl",
    "settings": {
      "_cssCustom": ".at-placeholder--xl {\\n\\t--min-height: 350px;\\n}",
        "at_framework": true,
        "at_version": "1.1.0"
    },
    "category": "at_placeholder"
  },
  {
    "id": "at_placeholder--icon-l",
    "name": "at-placeholder--icon-l",
    "settings": {
      "_cssCustom": ".at-placeholder--icon-l {\\n\\t--icon-size: 4rem;\\n}",
        "at_framework": true,
        "at_version": "1.1.0"
    },
    "category": "at_placeholder"
  },
  {
    "id": "at_placeholder--icon-xl",
    "name": "at-placeholder--icon-xl",
    "settings": {
      "_cssCustom": ".at-placeholder--icon-xl {\\n\\t--icon-size: 8rem;\\n}",
        "at_framework": true,
        "at_version": "1.1.0"
    },
    "category": "at_placeholder"
  },
  {
    "id": "at_placeholder--h1",
    "name": "at-placeholder--h1",
    "settings": {
      "_cssCustom": ".at-placeholder--h1 {\\n  --padding: 0 var(--at-space--s);\\n  --icon-size:var(--at-heading--xl);\\n  --min-height: 0;\\n\\t--icon-svg: url(\'data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" color=\"%23000000\" fill=\"none\"><path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M17.2929 10.2929C17.4804 10.1054 17.7348 10 18 10H18.5C19.0523 10 19.5 10.4477 19.5 11V18H20C20.5523 18 21 18.4477 21 19C21 19.5523 20.5523 20 20 20H17C16.4477 20 16 19.5523 16 19C16 18.4477 16.4477 18 17 18H17.5V12.8663C17.1176 13.0874 16.6201 13.0343 16.2929 12.7071C15.9024 12.3166 15.9024 11.6834 16.2929 11.2929L17.2929 10.2929Z\" fill=\"currentColor\" /><path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M4 4C4.55228 4 5 4.44772 5 5V11H12V5C12 4.44772 12.4477 4 13 4C13.5523 4 14 4.44772 14 5V19C14 19.5523 13.5523 20 13 20C12.4477 20 12 19.5523 12 19V13H5V19C5 19.5523 4.55228 20 4 20C3.44772 20 3 19.5523 3 19V5C3 4.44772 3.44772 4 4 4Z\" fill=\"currentColor\" /></svg>\')\\n}",
        "at_framework": true,
        "at_version": "1.1.0"
    },
    "category": "at_placeholder"
  },
  {
    "id": "at_placeholder--h2",
    "name": "at-placeholder--h2",
    "settings": {
      "_cssCustom": ".at-placeholder--h2 {\\n  row-gap: 0;\\n  --padding: 0 var(--at-space--s);\\n  --icon-size:var(--at-heading--l);\\n  --min-height: 0;\\n\\t--icon-svg: url(\'data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" color=\"%23000000\" fill=\"none\"><path d=\"M3.5 5V19\" stroke=\"%23000000\" stroke-width=\"1.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\"></path><path d=\"M13.5 5V19\" stroke=\"%23000000\" stroke-width=\"1.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\"></path><path d=\"M20.5 19H16.5V18.6907C16.5 18.2521 16.5 18.0327 16.5865 17.8385C16.673 17.6443 16.836 17.4976 17.1621 17.2041L19.7671 14.8596C20.2336 14.4397 20.5 13.8416 20.5 13.214V13C20.5 11.8954 19.6046 11 18.5 11C17.3954 11 16.5 11.8954 16.5 13V13.4\" stroke=\"%23000000\" stroke-width=\"1.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\"></path><path d=\"M3.5 12L13.5 12\" stroke=\"%23000000\" stroke-width=\"1.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\"></path></svg>\')\\n}",
        "at_framework": true,
        "at_version": "1.1.0"
    },
    "category": "at_placeholder"
  },
  {
    "id": "at_placeholder--chart",
    "name": "at-placeholder--chart",
    "settings": {
      "_cssCustom": ".at-placeholder--chart {\\n\\t--icon-svg: url(\'data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" color=\"%23000000\" fill=\"none\"><path opacity=\"0.4\" d=\"M20.5 10.5V19.5C20.5 19.9659 20.5 20.1989 20.4239 20.3827C20.3224 20.6277 20.1277 20.8224 19.8827 20.9239C19.6989 21 19.4659 21 19 21C18.5341 21 18.3011 21 18.1173 20.9239C17.8723 20.8224 17.6776 20.6277 17.5761 20.3827C17.5 20.1989 17.5 19.9659 17.5 19.5V10.5C17.5 10.0341 17.5 9.80109 17.5761 9.61732C17.6776 9.37229 17.8723 9.17761 18.1173 9.07612C18.3011 9 18.5341 9 19 9C19.4659 9 19.6989 9 19.8827 9.07612C20.1277 9.17761 20.3224 9.37229 20.4239 9.61732C20.5 9.80109 20.5 10.0341 20.5 10.5Z\" fill=\"currentColor\" /><path opacity=\"0.4\" d=\"M13.5 14V19.5C13.5 19.9659 13.5 20.1989 13.4239 20.3827C13.3224 20.6277 13.1277 20.8224 12.8827 20.9239C12.6989 21 12.4659 21 12 21C11.5341 21 11.3011 21 11.1173 20.9239C10.8723 20.8224 10.6776 20.6277 10.5761 20.3827C10.5 20.1989 10.5 19.9659 10.5 19.5V14C10.5 13.5341 10.5 13.3011 10.5761 13.1173C10.6776 12.8723 10.8723 12.6776 11.1173 12.5761C11.3011 12.5 11.5341 12.5 12 12.5C12.4659 12.5 12.6989 12.5 12.8827 12.5761C13.1277 12.6776 13.3224 12.8723 13.4239 13.1173C13.5 13.3011 13.5 13.5341 13.5 14Z\" fill=\"currentColor\" /><path opacity=\"0.4\" d=\"M6.5 16.5V19.5C6.5 19.9659 6.5 20.1989 6.42388 20.3827C6.32239 20.6277 6.12771 20.8224 5.88268 20.9239C5.69891 21 5.46594 21 5 21C4.53406 21 4.30109 21 4.11732 20.9239C3.87229 20.8224 3.67761 20.6277 3.57612 20.3827C3.5 20.1989 3.5 19.9659 3.5 19.5V16.5C3.5 16.0341 3.5 15.8011 3.57612 15.6173C3.67761 15.3723 3.87229 15.1776 4.11732 15.0761C4.30109 15 4.53406 15 5 15C5.46594 15 5.69891 15 5.88268 15.0761C6.12771 15.1776 6.32239 15.3723 6.42388 15.6173C6.5 15.8011 6.5 16.0341 6.5 16.5Z\" fill=\"currentColor\" /><path d=\"M20.5 10.5V19.5C20.5 19.9659 20.5 20.1989 20.4239 20.3827C20.3224 20.6277 20.1277 20.8224 19.8827 20.9239C19.6989 21 19.4659 21 19 21C18.5341 21 18.3011 21 18.1173 20.9239C17.8723 20.8224 17.6776 20.6277 17.5761 20.3827C17.5 20.1989 17.5 19.9659 17.5 19.5V10.5C17.5 10.0341 17.5 9.80109 17.5761 9.61732C17.6776 9.37229 17.8723 9.17761 18.1173 9.07612C18.3011 9 18.5341 9 19 9C19.4659 9 19.6989 9 19.8827 9.07612C20.1277 9.17761 20.3224 9.37229 20.4239 9.61732C20.5 9.80109 20.5 10.0341 20.5 10.5Z\" stroke=\"currentColor\" stroke-width=\"1.5\" stroke-linejoin=\"round\" /><path d=\"M16.5 3H19.5V6\" stroke=\"currentColor\" stroke-width=\"1.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\" /><path d=\"M19 3.5C19 3.5 15 8.5 4.5 12\" stroke=\"currentColor\" stroke-width=\"1.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\" /><path d=\"M13.5 14V19.5C13.5 19.9659 13.5 20.1989 13.4239 20.3827C13.3224 20.6277 13.1277 20.8224 12.8827 20.9239C12.6989 21 12.4659 21 12 21C11.5341 21 11.3011 21 11.1173 20.9239C10.8723 20.8224 10.6776 20.6277 10.5761 20.3827C10.5 20.1989 10.5 19.9659 10.5 19.5V14C10.5 13.5341 10.5 13.3011 10.5761 13.1173C10.6776 12.8723 10.8723 12.6776 11.1173 12.5761C11.3011 12.5 11.5341 12.5 12 12.5C12.4659 12.5 12.6989 12.5 12.8827 12.5761C13.1277 12.6776 13.3224 12.8723 13.4239 13.1173C13.5 13.3011 13.5 13.5341 13.5 14Z\" stroke=\"currentColor\" stroke-width=\"1.5\" stroke-linejoin=\"round\" /><path d=\"M6.5 16.5V19.5C6.5 19.9659 6.5 20.1989 6.42388 20.3827C6.32239 20.6277 6.12771 20.8224 5.88268 20.9239C5.69891 21 5.46594 21 5 21C4.53406 21 4.30109 21 4.11732 20.9239C3.87229 20.8224 3.67761 20.6277 3.57612 20.3827C3.5 20.1989 3.5 19.9659 3.5 19.5V16.5C3.5 16.0341 3.5 15.8011 3.57612 15.6173C3.67761 15.3723 3.87229 15.1776 4.11732 15.0761C4.30109 15 4.53406 15 5 15C5.46594 15 5.69891 15 5.88268 15.0761C6.12771 15.1776 6.32239 15.3723 6.42388 15.6173C6.5 15.8011 6.5 16.0341 6.5 16.5Z\" stroke=\"currentColor\" stroke-width=\"1.5\" stroke-linejoin=\"round\" /></svg>\')\\n}",
        "at_framework": true,
        "at_version": "1.1.0"
    },
    "category": "at_placeholder"
  },
  {
    "id": "at_placeholder--dashboard",
    "name": "at-placeholder--dashboard",
    "settings": {
      "_cssCustom": ".at-placeholder--dashboard {\\n\\t--icon-svg: url(\'data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" color=\"%23000000\" fill=\"none\"><path opacity=\"0.4\" d=\"M13.6903 19.4567C13.5 18.9973 13.5 18.4149 13.5 17.25C13.5 16.0851 13.5 15.5027 13.6903 15.0433C13.944 14.4307 14.4307 13.944 15.0433 13.6903C15.5027 13.5 16.0851 13.5 17.25 13.5C18.4149 13.5 18.9973 13.5 19.4567 13.6903C20.0693 13.944 20.556 14.4307 20.8097 15.0433C21 15.5027 21 16.0851 21 17.25C21 18.4149 21 18.9973 20.8097 19.4567C20.556 20.0693 20.0693 20.556 19.4567 20.8097C18.9973 21 18.4149 21 17.25 21C16.0851 21 15.5027 21 15.0433 20.8097C14.4307 20.556 13.944 20.0693 13.6903 19.4567Z\" fill=\"currentColor\" /><path opacity=\"0.4\" d=\"M3.1903 8.95671C3 8.49728 3 7.91485 3 6.75C3 5.58515 3 5.00272 3.1903 4.54329C3.44404 3.93072 3.93072 3.44404 4.54329 3.1903C5.00272 3 5.58515 3 6.75 3C7.91485 3 8.49728 3 8.95671 3.1903C9.56928 3.44404 10.056 3.93072 10.3097 4.54329C10.5 5.00272 10.5 5.58515 10.5 6.75C10.5 7.91485 10.5 8.49728 10.3097 8.95671C10.056 9.56928 9.56928 10.056 8.95671 10.3097C8.49728 10.5 7.91485 10.5 6.75 10.5C5.58515 10.5 5.00272 10.5 4.54329 10.3097C3.93072 10.056 3.44404 9.56928 3.1903 8.95671Z\" fill=\"currentColor\" /><path d=\"M17.25 3V4.33957M17.25 4.33957C17.9156 4.33957 18.5182 4.60934 18.9545 5.04551M17.25 4.33957C16.5844 4.33957 15.9818 4.60934 15.5455 5.04551M17.25 9.161V10.5M17.25 9.161C17.9158 9.161 18.5185 8.89111 18.9548 8.45477M17.25 9.161C16.5842 9.161 15.9815 8.89111 15.5452 8.45477M21 6.75L19.6607 6.75028M19.6607 6.75028C19.6607 7.41591 19.3909 8.01853 18.9548 8.45477M19.6607 6.75028C19.6607 6.08451 19.3908 5.48177 18.9545 5.04551M14.8393 6.75028L13.5 6.75M14.8393 6.75028C14.8393 6.08451 15.1092 5.48177 15.5455 5.04551M14.8393 6.75028C14.8393 7.41591 15.1091 8.01853 15.5452 8.45477M19.9017 4.09835L18.9545 5.04551M14.5984 9.40165L15.5452 8.45477M19.9017 9.40165L18.9548 8.45477M14.5984 4.09835L15.5455 5.04551\" stroke=\"currentColor\" stroke-width=\"1.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\" /><path d=\"M13.6903 19.4567C13.5 18.9973 13.5 18.4149 13.5 17.25C13.5 16.0851 13.5 15.5027 13.6903 15.0433C13.944 14.4307 14.4307 13.944 15.0433 13.6903C15.5027 13.5 16.0851 13.5 17.25 13.5C18.4149 13.5 18.9973 13.5 19.4567 13.6903C20.0693 13.944 20.556 14.4307 20.8097 15.0433C21 15.5027 21 16.0851 21 17.25C21 18.4149 21 18.9973 20.8097 19.4567C20.556 20.0693 20.0693 20.556 19.4567 20.8097C18.9973 21 18.4149 21 17.25 21C16.0851 21 15.5027 21 15.0433 20.8097C14.4307 20.556 13.944 20.0693 13.6903 19.4567Z\" stroke=\"currentColor\" stroke-width=\"1.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\" /><path d=\"M3.1903 19.4567C3 18.9973 3 18.4149 3 17.25C3 16.0851 3 15.5027 3.1903 15.0433C3.44404 14.4307 3.93072 13.944 4.54329 13.6903C5.00272 13.5 5.58515 13.5 6.75 13.5C7.91485 13.5 8.49728 13.5 8.95671 13.6903C9.56928 13.944 10.056 14.4307 10.3097 15.0433C10.5 15.5027 10.5 16.0851 10.5 17.25C10.5 18.4149 10.5 18.9973 10.3097 19.4567C10.056 20.0693 9.56928 20.556 8.95671 20.8097C8.49728 21 7.91485 21 6.75 21C5.58515 21 5.00272 21 4.54329 20.8097C3.93072 20.556 3.44404 20.0693 3.1903 19.4567Z\" stroke=\"currentColor\" stroke-width=\"1.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\" /><path d=\"M3.1903 8.95671C3 8.49728 3 7.91485 3 6.75C3 5.58515 3 5.00272 3.1903 4.54329C3.44404 3.93072 3.93072 3.44404 4.54329 3.1903C5.00272 3 5.58515 3 6.75 3C7.91485 3 8.49728 3 8.95671 3.1903C9.56928 3.44404 10.056 3.93072 10.3097 4.54329C10.5 5.00272 10.5 5.58515 10.5 6.75C10.5 7.91485 10.5 8.49728 10.3097 8.95671C10.056 9.56928 9.56928 10.056 8.95671 10.3097C8.49728 10.5 7.91485 10.5 6.75 10.5C5.58515 10.5 5.00272 10.5 4.54329 10.3097C3.93072 10.056 3.44404 9.56928 3.1903 8.95671Z\" stroke=\"currentColor\" stroke-width=\"1.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\" /></svg>\')\\n}",
        "at_framework": true,
        "at_version": "1.1.0"
    },
    "category": "at_placeholder"
  },
  {
    "id": "at_placeholder--form",
    "name": "at-placeholder--form",
    "settings": {
      "_cssCustom": ".at-placeholder--form {\\n  --icon-svg:url(\'data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" color=\"%23000000\" fill=\"none\"><path opacity=\"0.4\" d=\"M12.5564 1.25C14.3941 1.24998 15.8497 1.24997 16.989 1.40314C18.1614 1.56076 19.1103 1.89288 19.8587 2.64124C20.6071 3.38961 20.9392 4.33856 21.0968 5.51098C21.25 6.65018 21.2499 8.1058 21.2499 9.94354V9.94359V14.0564V14.0565C21.2499 15.8942 21.25 17.3498 21.0968 18.489C20.9392 19.6614 20.6071 20.6104 19.8587 21.3588C19.1103 22.1071 18.1614 22.4392 16.989 22.5969C15.8497 22.75 14.3941 22.75 12.5564 22.75H12.5563H11.4436H11.4435C9.60579 22.75 8.15016 22.75 7.01094 22.5969C5.83851 22.4392 4.88956 22.1071 4.14119 21.3587C3.39283 20.6104 3.06072 19.6614 2.9031 18.489C2.74994 17.3498 2.74997 15.8941 2.75 14.0564V14.0563L2.75004 9.94351C2.75004 8.10576 2.75004 6.65014 2.90321 5.51094C3.06085 4.33852 3.39297 3.38958 4.14133 2.64123C4.88969 1.89287 5.83863 1.56076 7.01105 1.40313C8.15025 1.24997 9.60587 1.24998 11.4436 1.25H11.4436H12.5563H12.5564Z\" fill=\"currentColor\" /><path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M7 7C7 6.44772 7.44772 6 8 6H16C16.5523 6 17 6.44772 17 7C17 7.55228 16.5523 8 16 8H8C7.44772 8 7 7.55228 7 7ZM7 12C7 11.4477 7.44772 11 8 11H16C16.5523 11 17 11.4477 17 12C17 12.5523 16.5523 13 16 13H8C7.44772 13 7 12.5523 7 12ZM7 17C7 16.4477 7.44772 16 8 16L12 16C12.5523 16 13 16.4477 13 17C13 17.5523 12.5523 18 12 18L8 18C7.44771 18 7 17.5523 7 17Z\" fill=\"currentColor\" /></svg>\')\\n}",
        "at_framework": true,
        "at_version": "1.1.0"
    },
    "category": "at_placeholder"
  },
  {
    "id": "at_placeholder--list",
    "name": "at-placeholder--list",
    "settings": {
      "_cssCustom": ".at-placeholder--list {\\n\\t--icon-svg: url(\'data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" color=\"%23000000\" fill=\"none\"><path opacity=\"0.4\" d=\"M2 3.4C2 2.24173 2.24173 2 3.4 2H20.6C21.7583 2 22 2.24173 22 3.4V4.6C22 5.75827 21.7583 6 20.6 6H3.4C2.24173 6 2 5.75827 2 4.6V3.4Z\" fill=\"%23000000\"></path><path opacity=\"0.4\" d=\"M2 19.4C2 18.2417 2.24173 18 3.4 18H20.6C21.7583 18 22 18.2417 22 19.4V20.6C22 21.7583 21.7583 22 20.6 22H3.4C2.24173 22 2 21.7583 2 20.6V19.4Z\" fill=\"%23000000\"></path><path d=\"M2 11.4C2 10.2417 2.24173 10 3.4 10H20.6C21.7583 10 22 10.2417 22 11.4V12.6C22 13.7583 21.7583 14 20.6 14H3.4C2.24173 14 2 13.7583 2 12.6V11.4Z\" stroke=\"%23000000\" stroke-width=\"1.5\" stroke-linecap=\"round\"></path><path d=\"M2 3.4C2 2.24173 2.24173 2 3.4 2H20.6C21.7583 2 22 2.24173 22 3.4V4.6C22 5.75827 21.7583 6 20.6 6H3.4C2.24173 6 2 5.75827 2 4.6V3.4Z\" stroke=\"%23000000\" stroke-width=\"1.5\" stroke-linecap=\"round\"></path><path d=\"M2 19.4C2 18.2417 2.24173 18 3.4 18H20.6C21.7583 18 22 18.2417 22 19.4V20.6C22 21.7583 21.7583 22 20.6 22H3.4C2.24173 22 2 21.7583 2 20.6V19.4Z\" stroke=\"%23000000\" stroke-width=\"1.5\" stroke-linecap=\"round\"></path></svg>\');\\n}",
        "at_framework": true,
        "at_version": "1.1.0"
    },
    "category": "at_placeholder"
  },
  {
    "id": "at_placeholder--review",
    "name": "at-placeholder--review",
    "settings": {
      "_cssCustom": ".at-placeholder--review {\\n\\t--icon-svg: url(\'data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" color=\"%23000000\" fill=\"none\"><path opacity=\"0.4\" fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M3.39124 3.89124C2 5.28249 2 7.52166 2 12C2 16.4783 2 18.7175 3.39124 20.1088C4.78249 21.5 7.02166 21.5 11.5 21.5C15.9783 21.5 18.2175 21.5 19.6088 20.1088C21 18.7175 21 16.4783 21 12C21 7.52166 21 5.28249 19.6088 3.89124C18.2175 2.5 15.9783 2.5 11.5 2.5C7.02166 2.5 4.78249 2.5 3.39124 3.89124ZM13.2437 9.49644L12.3638 7.72209C11.8889 6.7593 11.109 6.7593 10.629 7.72209L9.74911 9.49644C9.62912 9.74343 9.30916 9.98035 9.04419 10.0257L7.44936 10.2929C6.42947 10.4643 6.1945 11.2103 6.92442 11.9462L8.16428 13.1964C8.37426 13.4081 8.48925 13.8164 8.42425 14.1087L8.06929 15.6562C7.78932 16.8761 8.43925 17.355 9.50913 16.7148L11.004 15.8226C11.2789 15.6613 11.7239 15.6613 11.9939 15.8226L13.4887 16.7148C14.5636 17.355 15.2085 16.8812 14.9285 15.6562L14.5736 14.1087C14.5086 13.8164 14.6236 13.4081 14.8335 13.1964L16.0734 11.9462C16.8083 11.2103 16.5684 10.4643 15.5485 10.2929L13.9536 10.0257C13.6837 9.98035 13.3637 9.74343 13.2437 9.49644Z\" fill=\"currentColor\" /><path d=\"M2 12C2 7.52166 2 5.28249 3.39124 3.89124C4.78249 2.5 7.02166 2.5 11.5 2.5C15.9783 2.5 18.2175 2.5 19.6088 3.89124C21 5.28249 21 7.52166 21 12C21 16.4783 21 18.7175 19.6088 20.1088C18.2175 21.5 15.9783 21.5 11.5 21.5C7.02166 21.5 4.78249 21.5 3.39124 20.1088C2 18.7175 2 16.4783 2 12Z\" stroke=\"currentColor\" stroke-width=\"1.5\" stroke-linejoin=\"round\" /><path d=\"M12.3638 7.72209L13.2437 9.49644C13.3637 9.74344 13.6837 9.98035 13.9536 10.0257L15.5485 10.2929C16.5684 10.4643 16.8083 11.2103 16.0734 11.9462L14.8335 13.1964C14.6236 13.4081 14.5086 13.8164 14.5736 14.1087L14.9285 15.6562C15.2085 16.8812 14.5636 17.355 13.4887 16.7148L11.9939 15.8226C11.7239 15.6613 11.2789 15.6613 11.004 15.8226L9.50913 16.7148C8.43925 17.355 7.78932 16.8761 8.06929 15.6562L8.42425 14.1087C8.48925 13.8164 8.37426 13.4081 8.16428 13.1964L6.92442 11.9462C6.1945 11.2103 6.42947 10.4643 7.44936 10.2929L9.04419 10.0257C9.30916 9.98035 9.62912 9.74344 9.74911 9.49644L10.629 7.72209C11.109 6.7593 11.8889 6.7593 12.3638 7.72209Z\" stroke=\"currentColor\" stroke-width=\"1.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\" /></svg>\')\\n}",
        "at_framework": true,
        "at_version": "1.1.0"
    },
    "category": "at_placeholder"
  },
  {
    "id": "at_placeholder--slider",
    "name": "at-placeholder--slider",
    "settings": {
      "_cssCustom": ".at-placeholder--slider {\\n\\t--icon-svg: url(\'data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" color=\"%23000000\" fill=\"none\"><path opacity=\"0.4\" d=\"M12.0514 2.25C13.1349 2.24998 14.0204 2.24996 14.7195 2.34822C15.4502 2.45093 16.0838 2.67321 16.5895 3.2019C17.0952 3.7306 17.3078 4.39293 17.4061 5.15689C17.5 5.88775 17.5 6.81348 17.5 7.94631V16.0537C17.5 17.1865 17.5 18.1123 17.4061 18.8431C17.3078 19.6071 17.0952 20.2694 16.5895 20.7981C16.0838 21.3268 15.4502 21.5491 14.7195 21.6518C14.0204 21.75 13.1349 21.75 12.0514 21.75H12.0514H11.9486H11.9486C10.8651 21.75 9.97958 21.75 9.2805 21.6518C8.54976 21.5491 7.91622 21.3268 7.41052 20.7981C6.90481 20.2694 6.69219 19.6071 6.59395 18.8431C6.49996 18.1123 6.49998 17.1865 6.5 16.0537V16.0537V7.94631V7.94629C6.49998 6.81346 6.49996 5.88774 6.59395 5.15689C6.69219 4.39293 6.90481 3.7306 7.41052 3.2019C7.91622 2.67321 8.54976 2.45093 9.2805 2.34822C9.97958 2.24996 10.8651 2.24998 11.9486 2.25H11.9486H12.0514H12.0514Z\" fill=\"%23000000\"></path><path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M1.26782 6.81229C1.37146 6.26982 1.89523 5.91407 2.43771 6.0177C3.11726 6.14752 3.73107 6.40163 4.23435 6.91859C4.81242 7.51237 5.04466 8.24703 5.15007 9.0524C5.25013 9.81685 5.2501 10.7794 5.25006 11.9325V12.0674C5.2501 13.2204 5.25013 14.183 5.15007 14.9475C5.04466 15.7529 4.81242 16.4875 4.23435 17.0813C3.73107 17.5982 3.11726 17.8524 2.43771 17.9822C1.89523 18.0858 1.37146 17.7301 1.26782 17.1876C1.16419 16.6451 1.51994 16.1213 2.06242 16.0177C2.47681 15.9385 2.66809 15.823 2.8013 15.6862C2.95547 15.5278 3.08935 15.2811 3.16699 14.6879C3.24805 14.0686 3.25006 13.238 3.25006 11.9999C3.25006 10.7618 3.24805 9.93125 3.16699 9.31197C3.08935 8.71876 2.95547 8.47207 2.8013 8.31371C2.66809 8.17688 2.47681 8.06134 2.06242 7.98218C1.51994 7.87854 1.16419 7.35477 1.26782 6.81229Z\" fill=\"%23000000\"></path><path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M22.7319 6.81254C22.6283 6.27006 22.1045 5.91431 21.562 6.01795C20.8825 6.14777 20.2687 6.40187 19.7654 6.91883C19.1873 7.51262 18.9551 8.24727 18.8497 9.05265C18.7496 9.81709 18.7497 10.7797 18.7497 11.9328V12.0676C18.7497 13.2207 18.7496 14.1833 18.8497 14.9477C18.9551 15.7531 19.1873 16.4878 19.7654 17.0815C20.2687 17.5985 20.8825 17.8526 21.562 17.9824C22.1045 18.0861 22.6283 17.7303 22.7319 17.1878C22.8356 16.6454 22.4798 16.1216 21.9373 16.0179C21.523 15.9388 21.3317 15.8232 21.1985 15.6864C21.0443 15.5281 20.9104 15.2814 20.8328 14.6882C20.7517 14.0689 20.7497 13.2383 20.7497 12.0002C20.7497 10.7621 20.7517 9.9315 20.8328 9.31221C20.9104 8.719 21.0443 8.47231 21.1985 8.31396C21.3317 8.17712 21.5229 8.06158 21.9373 7.98242C22.4798 7.87879 22.8356 7.35501 22.7319 6.81254Z\" fill=\"%23000000\"></path></svg>\')\\n}",
        "at_framework": true,
        "at_version": "1.1.0"
    },
    "category": "at_placeholder"
  },
  {
    "id": "at_placeholder--video",
    "name": "at-placeholder--video",
    "settings": {
      "_cssCustom": ".at-placeholder--video {\\n\\t--icon-svg: url(\'data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" color=\"%23000000\" fill=\"none\"><path opacity=\"0.4\" fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M3.02513 5.02513C2 6.05025 2 7.70017 2 11V13C2 16.2998 2 17.9497 3.02513 18.9749C4.05025 20 5.70017 20 9 20H10C13.2998 20 14.9497 20 15.9749 18.9749C17 17.9497 17 16.2998 17 13V11C17 7.70017 17 6.05025 15.9749 5.02513C14.9497 4 13.2998 4 10 4H9C5.70017 4 4.05025 4 3.02513 5.02513ZM11.5 11C12.3284 11 13 10.3284 13 9.5C13 8.67157 12.3284 8 11.5 8C10.6716 8 10 8.67157 10 9.5C10 10.3284 10.6716 11 11.5 11Z\" fill=\"%23000000\"></path><path d=\"M2 11C2 7.70017 2 6.05025 3.02513 5.02513C4.05025 4 5.70017 4 9 4H10C13.2998 4 14.9497 4 15.9749 5.02513C17 6.05025 17 7.70017 17 11V13C17 16.2998 17 17.9497 15.9749 18.9749C14.9497 20 13.2998 20 10 20H9C5.70017 20 4.05025 20 3.02513 18.9749C2 17.9497 2 16.2998 2 13V11Z\" stroke=\"%23000000\" stroke-width=\"1.5\"></path><path d=\"M17 8.90585L17.1259 8.80196C19.2417 7.05623 20.2996 6.18336 21.1498 6.60482C22 7.02628 22 8.42355 22 11.2181V12.7819C22 15.5765 22 16.9737 21.1498 17.3952C20.2996 17.8166 19.2417 16.9438 17.1259 15.198L17 15.0941\" stroke=\"%23000000\" stroke-width=\"1.5\" stroke-linecap=\"round\"></path><circle cx=\"11.5\" cy=\"9.5\" r=\"1.5\" stroke=\"%23000000\" stroke-width=\"1.5\"></circle></svg>\');\\n}",
        "at_framework": true,
        "at_version": "1.1.0"
    },
    "category": "at_placeholder"
  },
  {
    "id": "at_placeholder--person",
    "name": "at-placeholder--person",
    "settings": {
      "_cssCustom": ".at-placeholder--person {\\n\\t--icon-svg: url(\'data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" color=\"%23000000\" fill=\"none\"><path opacity=\"0.4\" d=\"M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z\" fill=\"%23000000\"></path><path opacity=\"0.4\" d=\"M14 14H10C7.23858 14 5 16.2386 5 19C5 20.1046 5.89543 21 7 21H17C18.1046 21 19 20.1046 19 19C19 16.2386 16.7614 14 14 14Z\" fill=\"%23000000\"></path><path d=\"M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z\" stroke=\"%23000000\" stroke-width=\"1.5\"></path><path d=\"M14 14H10C7.23858 14 5 16.2386 5 19C5 20.1046 5.89543 21 7 21H17C18.1046 21 19 20.1046 19 19C19 16.2386 16.7614 14 14 14Z\" stroke=\"%23000000\" stroke-width=\"1.5\" stroke-linejoin=\"round\"></path></svg>\')\\n}"
    },
    "category": "at_placeholder"
  },
  {
    "id": "at_placeholder__label",
    "name": "at-placeholder__label",
    "settings": {
        "at_framework": true,
        "at_version": "1.1.0"
    },
    "category": "at_placeholder"
  },
  {
    "id": "at_anim-trigger--enter",
    "name": "at-anim-trigger--enter",
    "settings": {
      "_cssCustom": ".bricks-is-frontend .at-anim-trigger--enter {\\n   &:not(.at-anim-group),\\n   &.at-anim-group .at-anim-child{\\n     --_animation-name: var(--animation-name, animationDefault);\\n     --_animation-distance: var(--animation-distance, var(--at-entrance-animation--distance));\\n     --_translate-default: 0, var(--_animation-distance), 0;\\n     --_animation-easing: var(--animation-easing, var(--at-entrance-animation--easing));\\n     --_animation-image-easing: var(--animation-image-easing, var(--at-entrance-animation--image-easing));\\n     --_animation-layer-easing: var(--animation-layer-easing, var(--at-entrance-animation--layer-easing));\\n     --_animation-delay: var(--animation-delay, var(--at-entrance-animation--delay));\\n     --_animation-delay-multiplier: var(--animation-delay-multiplier, 1);\\n     --_animation-duration: var(--animation-duration, var(--at-entrance-animation--duration));\\n     --_animation-fill-mode: var(--animation-fill-mode, forwards);\\n     --_reveal-clip-path: var(--reveal-clip-path, polygon(0 0, 100% 0, 100% 0, 0 0));\\n     --_reveal-duration: var(--reveal-duration, var(--at-entrance-animation-reveal--duration));\\n     --_reveal-with-layer-duration: var(--reveal-with-layer-duration, var(--at-entrance-animation-reveal-with-layer--duration));\\n     --_layer-bg-color: var(--layer-bg-color, var(--at-entrance-animation--layer-bg-color));\\n     --_layer-animation: var(--layer-animation);\\n     --_layer-transform-origin--start: var(--layer-transform-origin--start);\\n     --_layer-transform-origin--end: var(--layer-transform-origin--end);\\n     --_layer-scale--start: var(--layer-scale--start);\\n     opacity: 0;\\n   }\\n \\n   &:not(.at-anim-group).entered,\\n   &.at-anim-group.entered .at-anim-child{\\n     animation-name: var(--_animation-name);\\n     animation-delay: calc(var(--_animation-delay-multiplier) * var(--_animation-delay));\\n     animation-duration: var(--_animation-duration);\\n     animation-timing-function: var(--_animation-easing);\\n     animation-fill-mode: var(--_animation-fill-mode);\\n   }\\n \\n   &.entered[class*=at-reveal--],\\n   &.entered [class*=at-reveal--] {\\n \\n     &:not(.at-anim-group),\\n     .at-anim-child {\\n       --animation-name: revealClipPathAnimation;\\n       --animation-easing: cubic-bezier(0.77, 0, 0.175, 1);\\n       --animation-duration: var(--_reveal-duration);\\n       --translate: 0, 0, 0;\\n \\n       &>* {\\n         animation-name: revealImgAnimation1;\\n         animation-delay: calc(var(--_animation-delay-multiplier) * var(--_animation-delay));\\n         animation-duration: var(--_reveal-duration);\\n         animation-timing-function: var(--_animation-image-easing);\\n         animation-fill-mode: forwards;\\n         will-change: transform, clip-path, opacity;\\n         backface-visibility: hidden;\\n       }\\n     }\\n   }\\n \\n   &.entered[class*=at-reveal-with-layer--],\\n   &.entered [class*=at-reveal-with-layer--] {\\n     --layer-animation: revealLayerAnimation;\\n     --animation-name: unset;\\n     --translate: 0, 0, 0;\\n     opacity: 1 !important;\\n     transform: scale(0);\\n     overflow: hidden;\\n     will-change: transform, opacity;\\n \\n     &>* {\\n       position: relative;\\n       transform: scale(0);\\n       opacity: 0;\\n       animation-name: revealImgAnimation2;\\n       animation-delay: calc(var(--_animation-delay-multiplier) * var(--_animation-delay));\\n       animation-duration: var(--_reveal-with-layer-duration);\\n       animation-timing-function: var(--_animation-image-easing);\\n       animation-fill-mode: forwards;\\n       will-change: transform, opacity;\\n       backface-visibility: hidden;\\n     }\\n \\n     &::before {\\n       content: \'\';\\n       position: absolute;\\n       inset: 0;\\n       background: var(--_layer-bg-color);\\n       animation-name: var(--_layer-animation);\\n       animation-delay: calc(var(--_animation-delay-multiplier) * var(--_animation-delay));\\n       animation-duration: var(--_reveal-with-layer-duration);\\n       animation-timing-function: var(--_animation-layer-easing);\\n       animation-fill-mode: forwards;\\n       transform-origin: var(--_layer-transform-origin--start);\\n       z-index: 2;\\n       will-change: transform;\\n       backface-visibility: hidden;\\n     }\\n   }\\n }\\n \\n @media (prefers-reduced-motion: reduce) {\\n \\n   .at-anim-trigger--enter.entered,\\n   .at-anim-trigger--enter.entered [class*=at-reveal--],\\n   .at-anim-trigger--enter.entered [class*=at-reveal-with-layer--],\\n   .at-anim-trigger--enter.entered [class*=at-reveal-with-layer--]::before {\\n     animation-duration: 0.01ms !important;\\n     animation-delay: 0.01ms !important;\\n   }\\n \\n   .bricks-is-frontend .at-anim-trigger--enter {\\n     opacity: 1 !important;\\n   }\\n }\\n \\n @keyframes animationDefault {\\n   from {\\n     transform: translate3d(var(--translate, var(--_translate-default)));\\n   }\\n \\n   to {\\n     opacity: 1;\\n     transform: translate3d(0, 0, 0);\\n   }\\n }\\n \\n @keyframes revealClipPathAnimation {\\n   0% {\\n     opacity: 1;\\n     clip-path: var(--_reveal-clip-path);\\n   }\\n \\n   100% {\\n     opacity: 1;\\n     clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%);\\n   }\\n }\\n \\n @keyframes revealLayerAnimation {\\n   0% {\\n     transform: scale3d(var(--_layer-scale--start));\\n   }\\n \\n   45% {\\n     transform: scale3d(1, 1, 1);\\n   }\\n \\n   55% {\\n     transform: scale3d(1, 1, 1);\\n     transform-origin: var(--_layer-transform-origin--start);\\n   }\\n \\n   56% {\\n     transform-origin: var(--_layer-transform-origin--end);\\n   }\\n \\n   100% {\\n     transform: scale3d(var(--_layer-scale--start));\\n   }\\n }\\n \\n @keyframes revealImgAnimation1 {\\n   0% {\\n     transform: scale(1.3);\\n   }\\n \\n   100% {\\n     transform: scale(1);\\n   }\\n }\\n \\n @keyframes revealImgAnimation2 {\\n   0% {\\n     transform: scale(0);\\n     opacity: 0;\\n   }\\n \\n   40% {\\n     transform: scale(0);\\n     opacity: 0;\\n   }\\n \\n   60% {\\n     transform: scale(1.1);\\n     opacity: 1;\\n   }\\n \\n   100% {\\n     transform: scale(1);\\n     opacity: 1;\\n   }\\n }",
      "_interactions": [
        {
          "id": "at_anim-trigger--enter",
          "trigger": "enterView",
          "rootMargin": "0px 0px -15px 0px",
          "action": "setAttribute",
          "actionAttributeKey": "class",
          "actionAttributeValue": "entered"
        }
      ],
      "at_framework": true,
      "at_version": "1.2.0"
    },
    "category": "at_entrance-animations",
    "description": "This is the main animation class that should be applied to each individual animation or animation group (excluding child animations). It automatically adds the .entered class to the element when it enters the viewport, triggering the defined entrance animations.\\n\\nBy default, it performs a bottom-to-top slide animation, but you can fully customize the effect, duration, and delay using utility classes or by adjusting the associated CSS variables."
  },
  {
    "id": "at_anim-trigger--hover",
    "name": "at-anim-trigger--hover",
    "settings": {
      "_cssCustom": ".bricks-is-frontend .at-anim-trigger--hover {\\n   &:not(.at-anim-group),\\n   &.at-anim-group .at-anim-child{\\n     --_animation-name: var(--animation-name, animationDefault);\\n     --_animation-distance: var(--animation-distance, var(--at-entrance-animation--distance));\\n     --_translate-default: 0, var(--_animation-distance), 0;\\n     --_animation-easing: var(--animation-easing, var(--at-entrance-animation--easing));\\n     --_animation-image-easing: var(--animation-image-easing, var(--at-entrance-animation--image-easing));\\n     --_animation-layer-easing: var(--animation-layer-easing, var(--at-entrance-animation--layer-easing));\\n     --_animation-delay: var(--animation-delay, var(--at-entrance-animation--delay));\\n     --_animation-delay-multiplier: var(--animation-delay-multiplier, 1);\\n     --_animation-duration: var(--animation-duration, var(--at-entrance-animation--duration));\\n     --_animation-fill-mode: var(--animation-fill-mode, forwards);\\n     --_reveal-clip-path: var(--reveal-clip-path, polygon(0 0, 100% 0, 100% 0, 0 0));\\n     --_reveal-duration: var(--reveal-duration, var(--at-entrance-animation-reveal--duration));\\n     --_reveal-with-layer-duration: var(--reveal-with-layer-duration, var(--at-entrance-animation-reveal-with-layer--duration));\\n     --_layer-bg-color: var(--layer-bg-color, var(--at-entrance-animation--layer-bg-color));\\n     --_layer-animation: var(--layer-animation);\\n     --_layer-transform-origin--start: var(--layer-transform-origin--start);\\n     --_layer-transform-origin--end: var(--layer-transform-origin--end);\\n     --_layer-scale--start: var(--layer-scale--start);\\n     opacity: 0;\\n   }\\n \\n   &:not(.at-anim-group).hovered,\\n   &.at-anim-group.hovered .at-anim-child{\\n     animation-name: var(--_animation-name);\\n     animation-delay: calc(var(--_animation-delay-multiplier) * var(--_animation-delay));\\n     animation-duration: var(--_animation-duration);\\n     animation-timing-function: var(--_animation-easing);\\n     animation-fill-mode: var(--_animation-fill-mode);\\n   }\\n \\n   &.hovered[class*=at-reveal--],\\n   &.hovered [class*=at-reveal--] {\\n \\n     &:not(.at-anim-group),\\n     .at-anim-child {\\n       --animation-name: revealClipPathAnimation;\\n       --animation-easing: cubic-bezier(0.77, 0, 0.175, 1);\\n       --animation-duration: var(--_reveal-duration);\\n       --translate: 0, 0, 0;\\n \\n       &>* {\\n         animation-name: revealImgAnimation1;\\n         animation-delay: calc(var(--_animation-delay-multiplier) * var(--_animation-delay));\\n         animation-duration: var(--_reveal-duration);\\n         animation-timing-function: var(--_animation-image-easing);\\n         animation-fill-mode: forwards;\\n         will-change: transform, clip-path, opacity;\\n         backface-visibility: hidden;\\n       }\\n     }\\n   }\\n \\n   &.hovered[class*=at-reveal-with-layer--],\\n   &.hovered [class*=at-reveal-with-layer--] {\\n     --layer-animation: revealLayerAnimation;\\n     --animation-name: unset;\\n     --translate: 0, 0, 0;\\n     opacity: 1 !important;\\n     transform: scale(0);\\n     overflow: hidden;\\n     will-change: transform, opacity;\\n \\n     &>* {\\n       position: relative;\\n       transform: scale(0);\\n       opacity: 0;\\n       animation-name: revealImgAnimation2;\\n       animation-delay: calc(var(--_animation-delay-multiplier) * var(--_animation-delay));\\n       animation-duration: var(--_reveal-with-layer-duration);\\n       animation-timing-function: var(--_animation-image-easing);\\n       animation-fill-mode: forwards;\\n       will-change: transform, opacity;\\n       backface-visibility: hidden;\\n     }\\n \\n     &::before {\\n       content: \'\';\\n       position: absolute;\\n       inset: 0;\\n       background: var(--_layer-bg-color);\\n       animation-name: var(--_layer-animation);\\n       animation-delay: calc(var(--_animation-delay-multiplier) * var(--_animation-delay));\\n       animation-duration: var(--_reveal-with-layer-duration);\\n       animation-timing-function: var(--_animation-layer-easing);\\n       animation-fill-mode: forwards;\\n       transform-origin: var(--_layer-transform-origin--start);\\n       z-index: 2;\\n       will-change: transform;\\n       backface-visibility: hidden;\\n     }\\n   }\\n }\\n \\n @media (prefers-reduced-motion: reduce) {\\n \\n   .at-anim-trigger--hover.hovered,\\n   .at-anim-trigger--hover.hovered [class*=at-reveal--],\\n   .at-anim-trigger--hover.hovered [class*=at-reveal-with-layer--],\\n   .at-anim-trigger--hover.hovered [class*=at-reveal-with-layer--]::before {\\n     animation-duration: 0.01ms !important;\\n     animation-delay: 0.01ms !important;\\n   }\\n \\n   .bricks-is-frontend .at-anim-trigger--hover {\\n     opacity: 1 !important;\\n   }\\n }\\n \\n @keyframes animationDefault {\\n   from {\\n     transform: translate3d(var(--translate, var(--_translate-default)));\\n   }\\n \\n   to {\\n     opacity: 1;\\n     transform: translate3d(0, 0, 0);\\n   }\\n }\\n \\n @keyframes revealClipPathAnimation {\\n   0% {\\n     opacity: 1;\\n     clip-path: var(--_reveal-clip-path);\\n   }\\n \\n   100% {\\n     opacity: 1;\\n     clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%);\\n   }\\n }\\n \\n @keyframes revealLayerAnimation {\\n   0% {\\n     transform: scale3d(var(--_layer-scale--start));\\n   }\\n \\n   45% {\\n     transform: scale3d(1, 1, 1);\\n   }\\n \\n   55% {\\n     transform: scale3d(1, 1, 1);\\n     transform-origin: var(--_layer-transform-origin--start);\\n   }\\n \\n   56% {\\n     transform-origin: var(--_layer-transform-origin--end);\\n   }\\n \\n   100% {\\n     transform: scale3d(var(--_layer-scale--start));\\n   }\\n }\\n \\n @keyframes revealImgAnimation1 {\\n   0% {\\n     transform: scale(1.3);\\n   }\\n \\n   100% {\\n     transform: scale(1);\\n   }\\n }\\n \\n @keyframes revealImgAnimation2 {\\n   0% {\\n     transform: scale(0);\\n     opacity: 0;\\n   }\\n \\n   40% {\\n     transform: scale(0);\\n     opacity: 0;\\n   }\\n \\n   60% {\\n     transform: scale(1.1);\\n     opacity: 1;\\n   }\\n \\n   100% {\\n     transform: scale(1);\\n     opacity: 1;\\n   }\\n }",
      "_interactions": [
        {
          "id": "at_anim-trigger-hover--mouveenter",
          "trigger": "mouseenter",
          "action": "setAttribute",
          "actionAttributeKey": "class",
          "actionAttributeValue": "hovered"
        },
        {
          "id": "at_anim-trigger-hover--mouveleave",
          "trigger": "mouseleave",
          "action": "removeAttribute",
          "actionAttributeKey": "class",
          "actionAttributeValue": "hovered"
        },
        {
          "id": "at_anim-trigger--focus",
          "trigger": "focus",
          "action": "setAttribute",
          "actionAttributeKey": "class",
          "actionAttributeValue": "hovered"
        },
        {
          "id": "at_anim-trigger--blur",
          "trigger": "blur",
          "action": "removeAttribute",
          "actionAttributeKey": "class",
          "actionAttributeValue": "hovered"
        }
      ],
      "at_framework": true,
      "at_version": "1.2.0"
    },
    "category": "at_entrance-animations",
    "description": "This is the main animation class that should be applied to each individual animation or animation group (excluding child animations). It automatically adds the .hovered class to the element when hover or focus the element, triggering the defined entrance animations. The class is removed when the focus/hover state is removed.\\n\\nBy default, it performs a bottom-to-top slide animation, but you can fully customize the effect, duration, and delay using utility classes or by adjusting the associated CSS variables."
  },
    {
        "id": "at_anim-repeat",
        "name": "at-anim-repeat",
        "settings": {
            "_interactions": [
                {
                    "id": "at_anim-repeat",
                    "trigger": "leaveView",
                    "action": "removeAttribute",
                    "actionAttributeKey": "class",
                    "actionAttributeValue": "entered"
                }
            ],
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "Apply this class to the element with anim-trigger if you want the animation—whether individual or part of a group—to repeat every time the element re-enters the viewport. Without this class, the animation will run only once."
    },
    {
        "id": "at_anim-group",
        "name": "at-anim-group",
        "settings": {
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "Apply this class to the same element as the anim-trigger. It enables a timeline-style animation, where all child elements are animated as soon as the group enters the viewport—regardless of whether the individual children are visible.\\n\\nYou can create a staggered effect by adjusting the delay value on each child. Once anim-group is added to the wrapper, be sure to assign the anim-child class to every element you want to animate within the timeline sequence."
    },
    {
        "id": "at_anim-child",
        "name": "at-anim-child",
        "settings": {
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "Apply this class to each element within an animation group to link it to the group’s animation timeline. You can customize the animation effect, duration, and delay for each child element individually to create a staggered animation sequence."
    },
    {
        "id": "at_anim-distance--l",
        "name": "at-anim-distance--l",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-distance--l:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-distance--l .at-anim-child {\\n  --animation-distance: var(--at-distance--l, 40px);\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "The animation distance defines how far the element moves during its entrance animation. Using the anim-distance--l class increases this distance, making the movement more noticeable and dramatic."
    },
    {
        "id": "at_anim-distance--m",
        "name": "at-anim-distance--m",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-distance--m:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-distance--m .at-anim-child {\\n  --animation-distance: var(--at-distance--m, 15px);\\n} \\n",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "The animation distance defines how far the element moves during its entrance animation. anim-distance--m sets a medium animation distance, creating a balanced movement that’s noticeable but not too strong. It offers a subtle yet clear entrance effect."
    },
    {
        "id": "at_anim-distance--s",
        "name": "at-anim-distance--s",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-distance--s:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-distance--s .at-anim-child {\\n  --animation-distance: var(--at-distance--s, 4px);\\n} \\n",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "The animation distance defines how far the element moves during its entrance animation. anim-distance--s applies a small animation distance, resulting in a gentle, minimal movement. It’s ideal for subtle animations that don’t draw too much attention."
    },
    {
        "id": "at_anim-duration--fast",
        "name": "at-anim-duration--fast",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-duration--fast:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-duration--fast .at-anim-child {\\n  --animation-duration: var(--at-duration--fast, 200ms);\\n  --reveal-duration: var(--at-duration--fast, 200ms);\\n  --reveal-with-layer-duration: var(--at-duration--fast, 200ms);\\n} \\n",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "Animation duration defines how long an animation takes to complete from start to finish. Adjusting the duration changes the speed of the visual effect—shorter durations feel snappier, while longer ones feel smoother and more gradual.\\n\\nat-anim-duration--fast sets a shorter animation duration, making the animation play quickly. This is useful when you want fast, responsive visual feedback without slowing down the user experience."
    },
    {
        "id": "at_anim-duration--medium",
        "name": "at-anim-duration--medium",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-duration--medium:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-duration--medium .at-anim-child {\\n  --animation-duration: var(--at-duration--medium, 800ms);\\n  --reveal-duration: var(--at-duration--medium, 800ms);\\n  --reveal-with-layer-duration: var(--at-duration--medium, 800ms);\\n} \\n",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "Animation duration defines how long an animation takes to complete from start to finish. Adjusting the duration changes the speed of the visual effect—shorter durations feel snappier, while longer ones feel smoother and more gradual.\\n\\nat-anim-duration--medium sets a standard animation duration, offering a balanced speed that feels natural and smooth. It\'s ideal for general-purpose animations where timing should neither feel rushed nor too slow."
    },
    {
        "id": "at_anim-duration--slow",
        "name": "at-anim-duration--slow",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-duration--slow:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-duration--slow .at-anim-child {\\n  --animation-duration: var(--at-duration--slow, 1500ms);\\n  --reveal-duration: var(--at-duration--slow, 1500ms);\\n  --reveal-with-layer-duration: var(--at-duration--slow, 1500ms);\\n}\\n",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "Animation duration defines how long an animation takes to complete from start to finish. Adjusting the duration changes the speed of the visual effect—shorter durations feel snappier, while longer ones feel smoother and more gradual.\\n\\nat-anim-duration--slow applies a longer animation duration, resulting in a slower, more relaxed motion. This is useful when you want to emphasize an element or create a more dramatic entrance effect."
    },
    {
        "id": "at_anim-delay--fast",
        "name": "at-anim-delay--fast",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-delay--fast:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-delay--fast .at-anim-child {\\n  --animation-delay: var(--at-duration--fast);\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "This class applies a slow delay to your animation."
    },
    {
        "id": "at_anim-delay--medium",
        "name": "at-anim-delay--medium",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-delay--medium:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-delay--medium .at-anim-child {\\n  --animation-delay: var(--at-duration--medium);\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "This class applies a slow delay to your animation."
    },
    {
        "id": "at_anim-delay--slow",
        "name": "at-anim-delay--slow",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-delay--slow:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-delay--slow .at-anim-child {\\n  --animation-delay: var(--at-duration--slow);\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "This class applies a slow delay to your animation."
    },
    {
        "id": "at_anim-stagger",
        "name": "at-anim-stagger",
        "settings": {
            "_cssCustom": ".at-anim-stagger > *{\\n  --animation-delay-multiplier: var(--at-index);\\n  --animation-delay: var(--at-entrance-animation-stagger--delay);\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "Apply this class on the wrapper of your animated items to create a stagger effect. Keep in mind it will target direct children only."
    },
    {
        "id": "at_anim-stagger--0",
        "name": "at-anim-stagger--0",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-stagger--0:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-stagger--0 .at-anim-child {\\n  --animation-delay-multiplier: 0;\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "This class applies a delay multiplier of 1 to your animation delay.\\nFor example, if your base delay is set to 200ms, the final delay will be calculated as: 1 × 200ms = 200ms."
    },
    {
        "id": "at_anim-stagger--1",
        "name": "at-anim-stagger--1",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-stagger--1:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-stagger--1 .at-anim-child {\\n  --animation-delay-multiplier: 1;\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "This class applies a delay multiplier of 1 to your animation delay.\\nFor example, if your base delay is set to 200ms, the final delay will be calculated as: 1 × 200ms = 200ms."
    },
    {
        "id": "at_anim-stagger--2",
        "name": "at-anim-stagger--2",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-stagger--2:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-stagger--2 .at-anim-child {\\n  --animation-delay-multiplier: 2;\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "This class applies a delay multiplier of 2 to your animation delay.\\nFor example, if your base delay is set to 200ms, the final delay will be calculated as: 2 × 200ms = 400ms."
    },
    {
        "id": "at_anim-stagger--3",
        "name": "at-anim-stagger--3",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-stagger--3:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-stagger--3 .at-anim-child {\\n  --animation-delay-multiplier: 3;\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "This class applies a delay multiplier of 3 to your animation delay.\\nFor example, if your base delay is set to 200ms, the final delay will be calculated as: 3 × 200ms = 600ms."
    },
    {
        "id": "at_anim-stagger--4",
        "name": "at-anim-stagger--4",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-stagger--4:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-stagger--4 .at-anim-child {\\n  --animation-delay-multiplier: 4;\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "This class applies a delay multiplier of 4 to your animation delay.\\nFor example, if your base delay is set to 200ms, the final delay will be calculated as: 4 × 200ms = 800ms."
    },
    {
        "id": "at_anim-stagger--5",
        "name": "at-anim-stagger--5",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-stagger--5:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-stagger--5 .at-anim-child {\\n  --animation-delay-multiplier: 5;\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "This class applies a delay multiplier of 5 to your animation delay.\\nFor example, if your base delay is set to 200ms, the final delay will be calculated as: 5× 200ms = 1000ms."
    },
    {
        "id": "at_anim-stagger--6",
        "name": "at-anim-stagger--6",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-stagger--6:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-stagger--6 .at-anim-child {\\n  --animation-delay-multiplier: 6;\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "This class applies a delay multiplier of 6 to your animation delay.\\nFor example, if your base delay is set to 200ms, the final delay will be calculated as: 6 × 200ms = 1200ms."
    },
    {
        "id": "at_anim-stagger--7",
        "name": "at-anim-stagger--7",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-stagger--7:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-stagger--7 .at-anim-child {\\n  --animation-delay-multiplier: 7;\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "This class applies a delay multiplier of 7 to your animation delay.\\nFor example, if your base delay is set to 200ms, the final delay will be calculated as: 7 × 200ms = 1400ms."
    },
    {
        "id": "at_anim-stagger--8",
        "name": "at-anim-stagger--8",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-stagger--8:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-stagger--8 .at-anim-child {\\n  --animation-delay-multiplier: 8;\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "This class applies a delay multiplier of 8 to your animation delay.\\nFor example, if your base delay is set to 200ms, the final delay will be calculated as: 8 × 200ms = 1600ms."
    },
    {
        "id": "at_anim-effect-fade-in",
        "name": "at-anim-effect-fade-in",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-effect-fade-in:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-effect-fade-in .at-anim-child {\\n  --translate: 0, 0, 0;\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "anim-effect classes define the type of animation applied to an element by modifying its underlying CSS keyframes. Keyframe animations control how an element changes over time—for example, moving, fading, or scaling—by defining specific states at different points during the animation.\\n\\nanim-effect-fade-in gradually increases the element’s opacity from transparent to fully visible without any movement, creating a smooth and subtle entrance effect."
    },
    {
        "id": "at_anim-effect-slide--bottom-to-top",
        "name": "at-anim-effect-slide--bottom-to-top",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-effect-slide--bottom-to-top:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-effect-slide--bottom-to-top .at-anim-child {\\n  --translate: 0, var(--animation-distance), 0;\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "anim-effect classes define the type of animation applied to an element by modifying its underlying CSS keyframes. Keyframe animations control how an element changes over time—for example, moving, fading, or scaling—by defining specific states at different points during the animation.\\n\\nanim-effect-slide--bottom-to-top animates the element by sliding it upward from the bottom while simultaneously fading it in."
    },
    {
        "id": "at_anim-effect-slide--left-to-right",
        "name": "at-anim-effect-slide--left-to-right",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-effect-slide--left-to-right:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-effect-slide--left-to-right .at-anim-child {\\n  --translate: calc(var(--animation-distance) * -1), 0, 0;\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "anim-effect classes define the type of animation applied to an element by modifying its underlying CSS keyframes. Keyframe animations control how an element changes over time—for example, moving, fading, or scaling—by defining specific states at different points during the animation.\\n\\nanim-effect-slide--left-to-right moves the element horizontally from the left side toward the right, combined with a fade-in effect for a seamless left-to-right reveal."
    },
    {
        "id": "at_anim-effect-slide--right-to-left",
        "name": "at-anim-effect-slide--right-to-left",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-effect-slide--right-to-left:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-effect-slide--right-to-left .at-anim-child {\\n  --translate: var(--animation-distance, 15px), 0, 0;\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "anim-effect classes define the type of animation applied to an element by modifying its underlying CSS keyframes. Keyframe animations control how an element changes over time—for example, moving, fading, or scaling—by defining specific states at different points during the animation.\\n\\nanim-effect-slide--right-to-left slides the element from the right side to the left, fading it in as it moves, producing a smooth right-to-left entrance animation."
    },
    {
        "id": "at_anim-effect-slide--top-to-bottom",
        "name": "at-anim-effect-slide--top-to-bottom",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-effect-slide--top-to-bottom:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-effect-slide--top-to-bottom .at-anim-child {\\n  --translate: 0, calc(var(--animation-distance, 15px) * -1), 0;\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "anim-effect classes define the type of animation applied to an element by modifying its underlying CSS keyframes. Keyframe animations control how an element changes over time—for example, moving, fading, or scaling—by defining specific states at different points during the animation.\\n\\nanim-effect-slide--top-to-bottom animates the element by sliding it downward from the top while gradually fading it in, creating a smooth entrance from above."
    },
    {
        "id": "at_anim-effect-bounce-in",
        "name": "at-anim-effect-bounce-in",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-effect-bounce-in:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-effect-bounce-in .at-anim-child {;\\n  --animation-name: bounceInAnimation;\\n  --animation-fill-mode: both;\\n}\\n\\n@keyframes bounceInAnimation {\\n  0% {\\n    transform: translateY(-500px);\\n    animation-timing-function: ease-in;\\n    opacity: 0;\\n  }\\n\\n  38% {\\n    transform: translateY(0);\\n    animation-timing-function: ease-out;\\n    opacity: 1;\\n  }\\n\\n  55% {\\n    transform: translateY(-65px);\\n    animation-timing-function: ease-in;\\n  }\\n\\n  72% {\\n    transform: translateY(0);\\n    animation-timing-function: ease-out;\\n  }\\n\\n  81% {\\n    transform: translateY(-28px);\\n    animation-timing-function: ease-in;\\n  }\\n\\n  90% {\\n    transform: translateY(0);\\n    animation-timing-function: ease-out;\\n  }\\n\\n  95% {\\n    transform: translateY(-8px);\\n    animation-timing-function: ease-in;\\n  }\\n\\n  100% {\\n    transform: translateY(0);\\n    animation-timing-function: ease-out;\\n    opacity: 1;\\n  }\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "anim-effect classes define the type of animation applied to an element by modifying its underlying CSS keyframes. Keyframe animations control how an element changes over time—for example, moving, fading, or scaling—by defining specific states at different points during the animation.\\n\\nanim-effect-bounce-in animates the element with a bouncing motion as it appears, combining scale and vertical movement to simulate a spring-like entrance."
    },
    {
        "id": "at_anim-effect-flip",
        "name": "at-anim-effect-flip",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-effect-flip:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-effect-flip .at-anim-child {\\n  --_rotate-x: var(--rotate-x, -180deg);\\n  --animation-name: flipAnimation;\\n  --animation-easing: cubic-bezier(0.250, 0.460, 0.450, 0.940);\\n  --animation-fill-mode: both;\\n}\\n\\n@keyframes flipAnimation {\\n  0% {\\n    transform: rotateX(var(--_rotate-x));\\n  }\\n\\n  100% {\\n    transform: rotateX(0);\\n    opacity: 1;\\n  }\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "anim-effect classes define the type of animation applied to an element by modifying its underlying CSS keyframes. Keyframe animations control how an element changes over time—for example, moving, fading, or scaling—by defining specific states at different points during the animation.\\n\\nanim-effect-flip makes the element flip in 3D from upside down to its normal position while fading in smoothly. It creates a natural flipping effect that stays visible once finished."
    },
    {
        "id": "at_anim-effect-puff-in",
        "name": "at-anim-effect-puff-in",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-effect-puff-in:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-effect-puff-in .at-anim-child {\\n  --_scale: var(--scale, 2);\\n  --_blur: var(--blur, 4px);\\n  --animation-name: puffInAnimation;\\n  --animation-fill-mode: both;\\n  --animation-easing: cubic-bezier(0.470, 0.000, 0.745, 0.715);\\n}\\n\\n@keyframes puffInAnimation {\\n  0% {\\n    transform: scale(var(--_scale));\\n    filter: blur(var(--_blur));\\n    opacity: 0;\\n  }\\n\\n  100% {\\n    transform: scale(1);\\n    filter: blur(0px);\\n    opacity: 1;\\n  }\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "anim-effect classes define the type of animation applied to an element by modifying its underlying CSS keyframes. Keyframe animations control how an element changes over time—for example, moving, fading, or scaling—by defining specific states at different points during the animation.\\n\\nanim-effect-puff-in makes the element appear by quickly shrinking from a larger, blurry state to its normal size and sharpness while fading in smoothly. It creates a soft “puff” effect as it shows up."
    },
    {
        "id": "at_anim-effect-roll-in",
        "name": "at-anim-effect-roll-in",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-effect-roll-in:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-effect-roll-in .at-anim-child {\\n  --_translate-x: var(--translate-x, -800px);\\n  --_rotate: var(--rotate, -540deg);\\n  --animation-name: rollInAnimation;\\n  --animation-easing: ease-out;\\n  --animation-fill-mode: both;\\n}\\n\\n@keyframes rollInAnimation {\\n  0% {\\n    transform: translateX(var(--_translate-x)) rotate(var(--_rotate));\\n    opacity: 0;\\n  }\\n\\n  100% {\\n    transform: translateX(0) rotate(0deg);\\n    opacity: 1;\\n  }\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "anim-effect classes define the type of animation applied to an element by modifying its underlying CSS keyframes. Keyframe animations control how an element changes over time—for example, moving, fading, or scaling—by defining specific states at different points during the animation.\\n\\nanim-effect-roll-in makes the element roll in from the left while fading in, combining a horizontal slide and a spinning motion until it settles into place."
    },
    {
        "id": "at_anim-effect-rotate-scale",
        "name": "at-anim-effect-rotate-scale",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-effect-rotate-scale:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-effect-rotate-scale .at-anim-child {\\n  --_rotate-scale: var(--rotate-scale, 2);\\n  --_rotate-z--half: var(--rotate-z--half,180deg);\\n  --_rotate-z--end: var(--rotate-z--end, 360deg);\\n  --animation-name: rotateScaleAnimation;\\n  --animation-fill-mode: both;\\n}\\n\\n@keyframes rotateScaleAnimation {\\n  0% {\\n\\n    transform: scale(1) rotateZ(0);\\n  }\\n\\n  50% {\\n\\n    transform: scale(var(--_rotate-scale)) rotateZ(var(--_rotate-z--half));\\n  }\\n\\n  100% {\\n    transform: scale(1) rotateZ(var(--_rotate-z--end));\\n    opacity: 1;\\n  }\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "anim-effect classes define the type of animation applied to an element by modifying its underlying CSS keyframes. Keyframe animations control how an element changes over time—for example, moving, fading, or scaling—by defining specific states at different points during the animation.\\n\\nanim-effect-rotate-scale makes the element grow larger while halfway spinning around, then it spins a full turn back to normal size and becomes fully visible."
    },
    {
        "id": "at_anim-effect-rotate1",
        "name": "at-anim-effect-rotate1",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-effect-rotate1:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-effect-rotate1 .at-anim-child {\\n  --_rotate: var(--rotate, 0);\\n  --animation-name: rotate1Animation;\\n  --animation-easing: cubic-bezier(0.250, 0.460, 0.450, 0.940);\\n  --animation-fill-mode: both;\\n}\\n\\n@keyframes rotate1Animation {\\n  from {\\n    transform: rotate(var(--_rotate));\\n  }\\n\\n  to {\\n    transform: rotate(360deg);\\n    opacity: 1;\\n  }\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "anim-effect classes define the type of animation applied to an element by modifying its underlying CSS keyframes. Keyframe animations control how an element changes over time—for example, moving, fading, or scaling—by defining specific states at different points during the animation.\\n\\nanim-effect-rotate1 spins the element all the way around once while fading it in, creating a smooth full rotation effect as it appears."
    },
    {
        "id": "at_anim-effect-rotate2",
        "name": "at-anim-effect-rotate2",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-effect-rotate2:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-effect-rotate2 .at-anim-child {\\n  --_rotate: var(--rotate. -45deg);\\n  --animation-name: rotate2Animation;\\n  --animation-easing: cubic-bezier(0.250, 0.460, 0.450, 0.940);\\n  --animation-fill-mode: both;\\n}\\n\\n@keyframes rotate2Animation {\\n  0% {\\n    transform: rotate(var(--_rotate));\\n    opacity: 0;\\n  }\\n\\n  100% {\\n    transform: rotate(0);\\n    opacity: 1;\\n  }\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "anim-effect classes define the type of animation applied to an element by modifying its underlying CSS keyframes. Keyframe animations control how an element changes over time—for example, moving, fading, or scaling—by defining specific states at different points during the animation.\\n\\nanim-effect-rotate2 starts the element rotated slightly to the left and invisible, then smoothly rotates it back to its normal position while fading it in."
    },
    {
        "id": "at_anim-effect-scale-down",
        "name": "at-anim-effect-scale-down",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-effect-scale-down:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-effect-scale-down .at-anim-child {\\n  --_scale: var(--scale, 2);\\n  --animation-name: scaleDownAnimation;\\n  --animation-easing: cubic-bezier(0.250, 0.460, 0.450, 0.940);\\n  --animation-fill-mode: both;\\n}\\n\\n@keyframes scaleDownAnimation {\\n  from {\\n    transform: scale(var(--_scale));\\n  }\\n\\n  to {\\n    transform: scale(1);\\n    opacity: 1;\\n  }\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "anim-effect classes define the type of animation applied to an element by modifying its underlying CSS keyframes. Keyframe animations control how an element changes over time—for example, moving, fading, or scaling—by defining specific states at different points during the animation.\\n\\nanim-effect-scale-down makes the element start larger and then smoothly shrink down to its normal size while fading in."
    },
    {
        "id": "at_anim-effect-scale-up",
        "name": "at-anim-effect-scale-up",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-effect-scale-up:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-effect-scale-up .at-anim-child {\\n  --_scale: var(--scale, 0);\\n  --animation-name: scaleUpAnimation;\\n  --animation-easing: cubic-bezier(0.250, 0.460, 0.450, 0.940);\\n  --animation-fill-mode: both;\\n}\\n\\n@keyframes scaleUpAnimation {\\n  from {\\n    transform: scale(var(--_scale));\\n  }\\n\\n  to {\\n    transform: scale(1);\\n    opacity: 1;\\n  }\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "anim-effect classes define the type of animation applied to an element by modifying its underlying CSS keyframes. Keyframe animations control how an element changes over time—for example, moving, fading, or scaling—by defining specific states at different points during the animation.\\n\\nanim-effect-scale-up makes the element start very small or invisible, then smoothly grow to its full size while fading in."
    },
    {
        "id": "at_anim-effect-slit-in",
        "name": "at-anim-effect-slit-in",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-effect-slit-in:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-effect-slit-in .at-anim-child {\\n  --_translate-z--start: var(--translate-z--start, -800px);\\n  --_rotate-y--start: var(--rotate-y--start, 90deg);\\n  --_translate-z--half: var(--translate-z--half, -160px);\\n  --_rotate-y--half: var(--rotate-y--half, 87deg);\\n  --animation-name: slitInAnimation;\\n  --animation-easing: ease-out;\\n  --animation-fill-mode: both;\\n}\\n\\n@keyframes slitInAnimation {\\n  0% {\\n    transform: translateZ(var(--_translate-z--start)) rotateY(var(--_rotate-y--start));\\n    opacity: 0;\\n  }\\n\\n  54% {\\n    transform: translateZ(var(--_translate-z--half)) rotateY(var(--_rotate-y--half));\\n    opacity: 1;\\n  }\\n\\n  100% {\\n    transform: translateZ(0) rotateY(0);\\n    opacity: 1;\\n  }\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "anim-effect classes define the type of animation applied to an element by modifying its underlying CSS keyframes. Keyframe animations control how an element changes over time—for example, moving, fading, or scaling—by defining specific states at different points during the animation.\\n\\nanim-effect-slit-in makes the element appear as if it’s coming from far away, rotating on its vertical axis (like a door swinging open) while moving closer and fading in smoothly."
    },
    {
        "id": "at_anim-effect-swing-in",
        "name": "at-anim-effect-swing-in",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-effect-swing-in:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-effect-swing-in .at-anim-child {\\n  --_rotate-x: var(--rotate-x, -100deg);\\n  --_origin: var(--origin, top);\\n  --animation-name: swingInAnimation;\\n  --animation-easing: cubic-bezier(0.175, 0.885, 0.320, 1.275);\\n  --animation-fill-mode: both;\\n}\\n\\n@keyframes swingInAnimation {\\n  0% {\\n    transform: rotateX(var(--_rotate-x));\\n    transform-origin: var(--_origin);\\n    opacity: 0;\\n  }\\n\\n  100% {\\n    transform: rotateX(0deg);\\n    transform-origin: var(--origin);\\n    opacity: 1;\\n  }\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "anim-effect classes define the type of animation applied to an element by modifying its underlying CSS keyframes. Keyframe animations control how an element changes over time—for example, moving, fading, or scaling—by defining specific states at different points during the animation.\\n\\nanim-effect-swing-in makes the element swing forward from the top, like a door opening, while fading in smoothly as it moves into place."
    },
    {
        "id": "at_anim-effect-swirl-in",
        "name": "at-anim-effect-swirl-in",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-effect-swirl-in:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-effect-swirl-in .at-anim-child {\\n  --_rotate:var(--rotate, -540deg);\\n  --_scale: var(--scale, 0);\\n  --animation-name: swirlInAnimation;\\n  --animation-easing: ease-out;\\n  --animation-fill-mode: both;\\n}\\n\\n@keyframes swirlInAnimation {\\n  0% {\\n            transform: rotate(var(--_rotate)) scale(var(--_scale));\\n    opacity: 0;\\n  }\\n  100% {\\n            transform: rotate(0) scale(1);\\n    opacity: 1;\\n  }\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "anim-effect classes define the type of animation applied to an element by modifying its underlying CSS keyframes. Keyframe animations control how an element changes over time—for example, moving, fading, or scaling—by defining specific states at different points during the animation.\\n\\nanim-effect-swirl-in makes the element spin around multiple times while growing from nothing to its full size, fading in as it swirls smoothly into view."
    },
    {
        "id": "at_anim-effect-tilt-in",
        "name": "at-anim-effect-tilt-in",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-anim-effect-tilt-in:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-anim-effect-tilt-in .at-anim-child {\\n  --_rotate-y: var(--rotate-y, 30deg);\\n  --_translate-y: var(--translate-y, -300px);\\n  --_skew-y: var(--skew-y, -30deg);\\n  --animation-name: tiltinAnimation;\\n  --animation-easing: cubic-bezier(0.250, 0.460, 0.450, 0.940);\\n  --animation-fill-mode: both;\\n}\\n\\n@keyframes tiltinAnimation {\\n  0% {\\n    transform: rotateY(var(--_rotate-y)) translateY(var(--_translate-y)) skewY(var(--_skew-y));\\n    opacity: 0;\\n  }\\n\\n  100% {\\n    transform: rotateY(0deg) translateY(0) skewY(0deg);\\n    opacity: 1;\\n  }\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "anim-effect classes define the type of animation applied to an element by modifying its underlying CSS keyframes. Keyframe animations control how an element changes over time—for example, moving, fading, or scaling—by defining specific states at different points during the animation.\\n\\nanim-effect-tilt-in makes the element appear by tilting and sliding down while fading in. It starts tilted and shifted up, then smoothly moves into its normal position and orientation as it becomes visible."
    },
    {
        "id": "at_reveal--bottom-to-top",
        "name": "at-reveal--bottom-to-top",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-reveal--bottom-to-top:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-reveal--bottom-to-top .at-anim-child {\\n  --animation-name: revealClipPathAnimation;\\n  --animation-easing: cubic-bezier(0.77, 0, 0.175, 1);\\n  --reveal-clip-path: polygon(0 100%, 100% 100%, 100% 100%, 0 100%);\\n  --translate: 0, 0, 0;\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "Reveal animations work especially well with images and videos. They combine a sliding effect created with clip-path and a zoom (kenburst) effect on the image. To get the zoom effect, be sure to apply the animation class to the image’s wrapper element—not directly on the image itself.\\n\\nreveal--bottom-to-top makes the image appear by sliding upward from the bottom."
    },
    {
        "id": "at_reveal--left-to-right",
        "name": "at-reveal--left-to-right",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-reveal--left-to-right:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-reveal--left-to-right .at-anim-child {\\n  --reveal-clip-path: polygon(0 0, 0 0, 0 100%, 0% 100%);\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "Reveal animations work especially well with images and videos. They combine a sliding effect created with clip-path and a zoom (kenburst) effect on the image. To get the zoom effect, be sure to apply the animation class to the image’s wrapper element—not directly on the image itself.\\n\\nreveal--left-to-right reveals the image by sliding it horizontally from left to right."
    },
    {
        "id": "at_reveal--right-to-left",
        "name": "at-reveal--right-to-left",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-reveal--right-to-left:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-reveal--right-to-left .at-anim-child {\\n  --reveal-clip-path: polygon(100% 0, 100% 0, 100% 100%, 100% 100%);\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "Reveal animations work especially well with images and videos. They combine a sliding effect created with clip-path and a zoom (kenburst) effect on the image. To get the zoom effect, be sure to apply the animation class to the image’s wrapper element—not directly on the image itself.\\n\\nreveal--left-to-right reveals the image by sliding it horizontally from right to left."
    },
    {
        "id": "at_reveal--top-to-bottom",
        "name": "at-reveal--top-to-bottom",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-reveal--top-to-bottom:not(:has(.at-anim-child)),\\n.bricks-is-frontend .at-reveal--top-to-bottom .at-anim-child {\\n  --reveal-clip-path: polygon(0 0, 100% 0, 100% 0, 0 0);\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "Reveal animations work especially well with images and videos. They combine a sliding effect created with clip-path and a zoom (kenburst) effect on the image. To get the zoom effect, be sure to apply the animation class to the image’s wrapper element—not directly on the image itself.\\n\\nreveal--top-to-bottom reveals the image by sliding it downward from the top to the bottom."
    },
    {
        "id": "at_reveal-with-layer--bottom-to-top",
        "name": "at-reveal-with-layer--bottom-to-top",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-reveal-with-layer--bottom-to-top {\\n  --layer-transform-origin--start: 100% 100%;\\n  --layer-transform-origin--end: 0% 100%;\\n  --layer-scale--start: 1,0,1;\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "Reveal with layer animations work like standard reveal effects but add an extra animated color layer on top. This creates a more dynamic and engaging transition. For best results, apply the animation to the image wrapper—not the image itself.\\n\\nreveal-with-layer--bottom-to-top makes the image appear by sliding upward from the bottom."
    },
    {
        "id": "at_reveal-with-layer--left-to-right",
        "name": "at-reveal-with-layer--left-to-right",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-reveal-with-layer--left-to-right {\\n  --layer-transform-origin--start: 0% 0%;\\n  --layer-transform-origin--end: 0% 100%;\\n  --layer-scale--start: 0,1,1;\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "Reveal with layer animations work like standard reveal effects but add an extra animated color layer on top. This creates a more dynamic and engaging transition. For best results, apply the animation to the image wrapper—not the image itself.\\n\\nreveal-with-layer--left-to-right the image by sliding it horizontally from left to right."
    },
    {
        "id": "at_reveal-with-layer--right-to-left",
        "name": "at-reveal-with-layer--right-to-left",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-reveal-with-layer--right-to-left {\\n  --layer-transform-origin--start: 100% 100%;\\n  --layer-transform-origin--end: 100% 0%;\\n  --layer-scale--start: 0,1,1;\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "Reveal with layer animations work like standard reveal effects but add an extra animated color layer on top. This creates a more dynamic and engaging transition. For best results, apply the animation to the image wrapper—not the image itself.\\n\\nreveal-with-layer--left-to-right the image by sliding it horizontally from right to left."
    },
    {
        "id": "at_reveal-with-layer--top-to-bottom",
        "name": "at-reveal-with-layer--top-to-bottom",
        "settings": {
            "_cssCustom": ".bricks-is-frontend .at-reveal-with-layer--top-to-bottom {\\n  --layer-transform-origin--start: 0% 0%;\\n  --layer-transform-origin--end: 100% 0%;\\n  --layer-scale--start: 1,0,1;\\n}",
            "at_framework": true,
            "at_version": "1.2.0"
        },
        "category": "at_entrance-animations",
        "description": "Reveal with layer animations work like standard reveal effects but add an extra animated color layer on top. This creates a more dynamic and engaging transition. For best results, apply the animation to the image wrapper—not the image itself.\\n\\nreveal-with-layer--top-to-bottom reveals the image by sliding it downward from the top to the bottom."
    }
]',
            "global_colors" => '
                [
                    {
                        "id": "at_framework_palette",
                        "name": "AT Framework",
                        "at_framework": true,
                        "at_version": "1.0.0",
                        "status": "enabled",
                        "prefix": "at-",
                        "defaultExpanded": true,
                        "default": "true",
                        "colors": [
                            {
                                "id": "at_primary",
                                "name": "primary",
                                "raw": "var(--at-primary)",
                                "rawValue": {
                                    "light": "hsla(182, 68%, 49%, 1)",
                                    "dark": "hsla(182, 68%, 49%, 1)"
                                },
                                "complementaryChildren": [],
                                "shadeChildren": [
                                    "at_primary-l-1",
                                    "at_primary-l-2",
                                    "at_primary-l-3",
                                    "at_primary-l-4",
                                    "at_primary-l-5",
                                    "at_primary-l-6",
                                    "at_primary-d-1",
                                    "at_primary-d-2",
                                    "at_primary-d-3",
                                    "at_primary-d-4",
                                    "at_primary-d-5",
                                    "at_primary-d-6",
                                    "at_primary-t-1",
                                    "at_primary-t-2",
                                    "at_primary-t-3",
                                    "at_primary-t-4",
                                    "at_primary-t-5",
                                    "at_primary-t-6"
                                ],
                                "isExpanded": false,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-l-1",
                                "name": "primary-l-1",
                                "raw": "var(--at-primary-l-1)",
                                "rawValue": {
                                    "light": "hsla(182.1,65.3%,57.06%,1)",
                                    "dark": "hsla(182.1,65.3%,42.94%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_primary",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-l-2",
                                "name": "primary-l-2",
                                "raw": "var(--at-primary-l-2)",
                                "rawValue": {
                                    "light": "hsla(182.09,64.97%,65.29%,1)",
                                    "dark": "hsla(182.09,64.97%,34.71%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_primary",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-l-3",
                                "name": "primary-l-3",
                                "raw": "var(--at-primary-l-3)",
                                "rawValue": {
                                    "light": "hsla(182.02,65.93%,73.53%,1)",
                                    "dark": "hsla(182.02,65.93%,26.47%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_primary",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-l-4",
                                "name": "primary-l-4",
                                "raw": "var(--at-primary-l-4)",
                                "rawValue": {
                                    "light": "hsla(181.94,65.96%,81.57%,1)",
                                    "dark": "hsla(181.94,65.96%,18.43%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_primary",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-l-5",
                                "name": "primary-l-5",
                                "raw": "var(--at-primary-l-5)",
                                "rawValue": {
                                    "light": "hsla(181.76,65.38%,89.8%,1)",
                                    "dark": "hsla(181.76,65.38%,10.2%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_primary",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-l-6",
                                "name": "primary-l-6",
                                "raw": "var(--at-primary-l-6)",
                                "rawValue": {
                                    "light": "hsla(180,63.64%,97.84%,1)",
                                    "dark": "hsla(180,63.64%,2.16%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_primary",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-d-1",
                                "name": "primary-d-1",
                                "raw": "var(--at-primary-d-1)",
                                "rawValue": {
                                    "light": "hsla(182.07,68.08%,41.76%,1)",
                                    "dark": "hsla(182.07,68.08%,58.24%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_primary",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-d-2",
                                "name": "primary-d-2",
                                "raw": "var(--at-primary-d-2)",
                                "rawValue": {
                                    "light": "hsla(182.02,68%,34.31%,1)",
                                    "dark": "hsla(182.02,68%,65.69%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_primary",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-d-3",
                                "name": "primary-d-3",
                                "raw": "var(--at-primary-d-3)",
                                "rawValue": {
                                    "light": "hsla(181.91,68.12%,27.06%,1)",
                                    "dark": "hsla(181.91,68.12%,72.94%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_primary",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-d-4",
                                "name": "primary-d-4",
                                "raw": "var(--at-primary-d-4)",
                                "rawValue": {
                                    "light": "hsla(181.76,68%,19.61%,1)",
                                    "dark": "hsla(181.76,68%,80.39%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_primary",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-d-5",
                                "name": "primary-d-5",
                                "raw": "var(--at-primary-d-5)",
                                "rawValue": {
                                    "light": "hsla(181.4,68.25%,12.35%,1)",
                                    "dark": "hsla(181.4,68.25%,87.65%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_primary",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-d-6",
                                "name": "primary-d-6",
                                "raw": "var(--at-primary-d-6)",
                                "rawValue": {
                                    "light": "hsla(180,68%,4.9%,1)",
                                    "dark": "hsla(180,68%,95.1%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_primary",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-t-1",
                                "name": "primary-t-1",
                                "raw": "var(--at-primary-t-1)",
                                "rawValue": {
                                    "light": "hsla(182.12,68%,49.02%,0.84)",
                                    "dark": "hsla(182.12,68%,50.98%,0.84)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_primary",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-t-2",
                                "name": "primary-t-2",
                                "raw": "var(--at-primary-t-2)",
                                "rawValue": {
                                    "light": "hsla(182.12,68%,49.02%,0.68)",
                                    "dark": "hsla(182.12,68%,50.98%,0.68)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_primary",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-t-3",
                                "name": "primary-t-3",
                                "raw": "var(--at-primary-t-3)",
                                "rawValue": {
                                    "light": "hsla(182.12,68%,49.02%,0.53)",
                                    "dark": "hsla(182.12,68%,50.98%,0.53)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_primary",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-t-4",
                                "name": "primary-t-4",
                                "raw": "var(--at-primary-t-4)",
                                "rawValue": {
                                    "light": "hsla(182.12,68%,49.02%,0.37)",
                                    "dark": "hsla(182.12,68%,50.98%,0.37)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_primary",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-t-5",
                                "name": "primary-t-5",
                                "raw": "var(--at-primary-t-5)",
                                "rawValue": {
                                    "light": "hsla(182.12,68%,49.02%,0.21)",
                                    "dark": "hsla(182.12,68%,50.98%,0.21)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_primary",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-t-6",
                                "name": "primary-t-6",
                                "raw": "var(--at-primary-t-6)",
                                "rawValue": {
                                    "light": "hsla(182.12,68%,49.02%,0.05)",
                                    "dark": "hsla(182.12,68%,50.98%,0.05)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_primary",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary",
                                "name": "secondary",
                                "raw": "var(--at-secondary)",
                                "rawValue": {
                                    "light": "hsla(53, 65%, 55%, 1)",
                                    "dark": "hsla(53, 65%, 55%, 1)"
                                },
                                "complementaryChildren": [],
                                "shadeChildren": [
                                    "at_secondary-l-1",
                                    "at_secondary-l-2",
                                    "at_secondary-l-3",
                                    "at_secondary-l-4",
                                    "at_secondary-l-5",
                                    "at_secondary-l-6",
                                    "at_secondary-d-1",
                                    "at_secondary-d-2",
                                    "at_secondary-d-3",
                                    "at_secondary-d-4",
                                    "at_secondary-d-5",
                                    "at_secondary-d-6",
                                    "at_secondary-t-1",
                                    "at_secondary-t-2",
                                    "at_secondary-t-3",
                                    "at_secondary-t-4",
                                    "at_secondary-t-5",
                                    "at_secondary-t-6"
                                ],
                                "isExpanded": false,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-l-1",
                                "name": "secondary-l-1",
                                "raw": "var(--at-secondary-l-1)",
                                "rawValue": {
                                    "light": "hsla(52.8,65.45%,62.55%,1)",
                                    "dark": "hsla(52.8,65.45%,37.45%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_secondary",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-l-2",
                                "name": "secondary-l-2",
                                "raw": "var(--at-secondary-l-2)",
                                "rawValue": {
                                    "light": "hsla(52.8,64.94%,69.8%,1)",
                                    "dark": "hsla(52.8,64.94%,30.2%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_secondary",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-l-3",
                                "name": "secondary-l-3",
                                "raw": "var(--at-secondary-l-3)",
                                "rawValue": {
                                    "light": "hsla(52.8,64.1%,77.06%,1)",
                                    "dark": "hsla(52.8,64.1%,22.94%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_secondary",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-l-4",
                                "name": "secondary-l-4",
                                "raw": "var(--at-secondary-l-4)",
                                "rawValue": {
                                    "light": "hsla(52.08,65.43%,84.12%,1)",
                                    "dark": "hsla(52.08,65.43%,15.88%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_secondary",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-l-5",
                                "name": "secondary-l-5",
                                "raw": "var(--at-secondary-l-5)",
                                "rawValue": {
                                    "light": "hsla(51.72,64.44%,91.18%,1)",
                                    "dark": "hsla(51.72,64.44%,8.82%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_secondary",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-l-6",
                                "name": "secondary-l-6",
                                "raw": "var(--at-secondary-l-6)",
                                "rawValue": {
                                    "light": "hsla(50,60%,98.04%,1)",
                                    "dark": "hsla(50,60%,1.96%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_secondary",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-d-1",
                                "name": "secondary-d-1",
                                "raw": "var(--at-secondary-d-1)",
                                "rawValue": {
                                    "light": "hsla(52.8,53.65%,45.69%,1)",
                                    "dark": "hsla(52.8,53.65%,54.31%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_secondary",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-d-2",
                                "name": "secondary-d-2",
                                "raw": "var(--at-secondary-d-2)",
                                "rawValue": {
                                    "light": "hsla(52.87,54.01%,36.67%,1)",
                                    "dark": "hsla(52.87,54.01%,63.33%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_secondary",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-d-3",
                                "name": "secondary-d-3",
                                "raw": "var(--at-secondary-d-3)",
                                "rawValue": {
                                    "light": "hsla(52.99,53.85%,28.04%,1)",
                                    "dark": "hsla(52.99,53.85%,71.96%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_secondary",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-d-4",
                                "name": "secondary-d-4",
                                "raw": "var(--at-secondary-d-4)",
                                "rawValue": {
                                    "light": "hsla(53.57,56%,19.61%,1)",
                                    "dark": "hsla(53.57,56%,80.39%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_secondary",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-d-5",
                                "name": "secondary-d-5",
                                "raw": "var(--at-secondary-d-5)",
                                "rawValue": {
                                    "light": "hsla(51.67,58.06%,12.16%,1)",
                                    "dark": "hsla(51.67,58.06%,87.84%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_secondary",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-d-6",
                                "name": "secondary-d-6",
                                "raw": "var(--at-secondary-d-6)",
                                "rawValue": {
                                    "light": "hsla(52.94,68%,4.9%,1)",
                                    "dark": "hsla(52.94,68%,95.1%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_secondary",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-t-1",
                                "name": "secondary-t-1",
                                "raw": "var(--at-secondary-t-1)",
                                "rawValue": {
                                    "light": "hsla(52.75,65.07%,55.1%,0.84)",
                                    "dark": "hsla(52.75,65.07%,44.9%,0.84)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_secondary",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-t-2",
                                "name": "secondary-t-2",
                                "raw": "var(--at-secondary-t-2)",
                                "rawValue": {
                                    "light": "hsla(52.75,65.07%,55.1%,0.68)",
                                    "dark": "hsla(52.75,65.07%,44.9%,0.68)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_secondary",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-t-3",
                                "name": "secondary-t-3",
                                "raw": "var(--at-secondary-t-3)",
                                "rawValue": {
                                    "light": "hsla(52.75,65.07%,55.1%,0.53)",
                                    "dark": "hsla(52.75,65.07%,44.9%,0.53)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_secondary",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-t-4",
                                "name": "secondary-t-4",
                                "raw": "var(--at-secondary-t-4)",
                                "rawValue": {
                                    "light": "hsla(52.75,65.07%,55.1%,0.37)",
                                    "dark": "hsla(52.75,65.07%,44.9%,0.37)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_secondary",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-t-5",
                                "name": "secondary-t-5",
                                "raw": "var(--at-secondary-t-5)",
                                "rawValue": {
                                    "light": "hsla(52.75,65.07%,55.1%,0.21)",
                                    "dark": "hsla(52.75,65.07%,44.9%,0.21)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_secondary",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-t-6",
                                "name": "secondary-t-6",
                                "raw": "var(--at-secondary-t-6)",
                                "rawValue": {
                                    "light": "hsla(52.75,65.07%,55.1%,0.05)",
                                    "dark": "hsla(52.75,65.07%,44.9%,0.05)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_secondary",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral",
                                "name": "neutral",
                                "raw": "var(--at-neutral)",
                                "rawValue": {
                                    "light": "hsla(182, 8%, 50%, 1)",
                                    "dark": "hsla(182, 8%, 50%, 1)"
                                },
                                "complementaryChildren": [],
                                "shadeChildren": [
                                    "at_neutral-l-1",
                                    "at_neutral-l-2",
                                    "at_neutral-l-3",
                                    "at_neutral-l-4",
                                    "at_neutral-l-5",
                                    "at_neutral-l-6",
                                    "at_neutral-d-1",
                                    "at_neutral-d-2",
                                    "at_neutral-d-3",
                                    "at_neutral-d-4",
                                    "at_neutral-d-5",
                                    "at_neutral-d-6",
                                    "at_neutral-t-1",
                                    "at_neutral-t-2",
                                    "at_neutral-t-3",
                                    "at_neutral-t-4",
                                    "at_neutral-t-5",
                                    "at_neutral-t-6"
                                ],
                                "isExpanded": false,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-l-1",
                                "name": "neutral-l-1",
                                "raw": "var(--at-neutral-l-1)",
                                "rawValue": {
                                    "light": "hsla(183.33,8.41%,58.04%,1)",
                                    "dark": "hsla(183.33,8.41%,41.96%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_neutral",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-l-2",
                                "name": "neutral-l-2",
                                "raw": "var(--at-neutral-l-2)",
                                "rawValue": {
                                    "light": "hsla(180,8.05%,65.88%,1)",
                                    "dark": "hsla(180,8.05%,34.12%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_neutral",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-l-3",
                                "name": "neutral-l-3",
                                "raw": "var(--at-neutral-l-3)",
                                "rawValue": {
                                    "light": "hsla(180,8.27%,73.92%,1)",
                                    "dark": "hsla(180,8.27%,26.08%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_neutral",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-l-4",
                                "name": "neutral-l-4",
                                "raw": "var(--at-neutral-l-4)",
                                "rawValue": {
                                    "light": "hsla(187.5,8.7%,81.96%,1)",
                                    "dark": "hsla(187.5,8.7%,18.04%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_neutral",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-l-5",
                                "name": "neutral-l-5",
                                "raw": "var(--at-neutral-l-5)",
                                "rawValue": {
                                    "light": "hsla(180,7.69%,89.8%,1)",
                                    "dark": "hsla(180,7.69%,10.2%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_neutral",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-l-6",
                                "name": "neutral-l-6",
                                "raw": "var(--at-neutral-l-6)",
                                "rawValue": {
                                    "light": "hsla(180,9.09%,97.84%,1)",
                                    "dark": "hsla(180,9.09%,2.16%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_neutral",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-d-1",
                                "name": "neutral-d-1",
                                "raw": "var(--at-neutral-d-1)",
                                "rawValue": {
                                    "light": "hsla(180,7.83%,42.55%,1)",
                                    "dark": "hsla(180,7.83%,57.45%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_neutral",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-d-2",
                                "name": "neutral-d-2",
                                "raw": "var(--at-neutral-d-2)",
                                "rawValue": {
                                    "light": "hsla(184,8.38%,35.1%,1)",
                                    "dark": "hsla(184,8.38%,64.9%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_neutral",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-d-3",
                                "name": "neutral-d-3",
                                "raw": "var(--at-neutral-d-3)",
                                "rawValue": {
                                    "light": "hsla(180,7.8%,27.65%,1)",
                                    "dark": "hsla(180,7.8%,72.35%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_neutral",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-d-4",
                                "name": "neutral-d-4",
                                "raw": "var(--at-neutral-d-4)",
                                "rawValue": {
                                    "light": "hsla(180,7.84%,20%,1)",
                                    "dark": "hsla(180,7.84%,80%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_neutral",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-d-5",
                                "name": "neutral-d-5",
                                "raw": "var(--at-neutral-d-5)",
                                "rawValue": {
                                    "light": "hsla(180,7.69%,12.75%,1)",
                                    "dark": "hsla(180,7.69%,87.25%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_neutral",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-d-6",
                                "name": "neutral-d-6",
                                "raw": "var(--at-neutral-d-6)",
                                "rawValue": {
                                    "light": "hsla(180,7.69%,5.1%,1)",
                                    "dark": "hsla(180,7.69%,94.9%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_neutral",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-t-1",
                                "name": "neutral-t-1",
                                "raw": "var(--at-neutral-t-1)",
                                "rawValue": {
                                    "light": "hsla(182.86,8.24%,50%,0.84)",
                                    "dark": "hsla(182.86,8.24%,50%,0.84)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_neutral",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-t-2",
                                "name": "neutral-t-2",
                                "raw": "var(--at-neutral-t-2)",
                                "rawValue": {
                                    "light": "hsla(182.86,8.24%,50%,0.68)",
                                    "dark": "hsla(182.86,8.24%,50%,0.68)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_neutral",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-t-3",
                                "name": "neutral-t-3",
                                "raw": "var(--at-neutral-t-3)",
                                "rawValue": {
                                    "light": "hsla(182.86,8.24%,50%,0.53)",
                                    "dark": "hsla(182.86,8.24%,50%,0.53)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_neutral",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-t-4",
                                "name": "neutral-t-4",
                                "raw": "var(--at-neutral-t-4)",
                                "rawValue": {
                                    "light": "hsla(182.86,8.24%,50%,0.37)",
                                    "dark": "hsla(182.86,8.24%,50%,0.37)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_neutral",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-t-5",
                                "name": "neutral-t-5",
                                "raw": "var(--at-neutral-t-5)",
                                "rawValue": {
                                    "light": "hsla(182.86,8.24%,50%,0.21)",
                                    "dark": "hsla(182.86,8.24%,50%,0.21)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_neutral",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-t-6",
                                "name": "neutral-t-6",
                                "raw": "var(--at-neutral-t-6)",
                                "rawValue": {
                                    "light": "hsla(182.86,8.24%,50%,0.05)",
                                    "dark": "hsla(182.86,8.24%,50%,0.05)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_neutral",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black",
                                "name": "black",
                                "raw": "var(--at-black)",
                                "rawValue": {
                                    "light": "hsla(0, 0%, 0%, 1)",
                                    "dark": "#ffffff"
                                },
                                "complementaryChildren": [],
                                "shadeChildren": [
                                    "at_black-t-1",
                                    "at_black-t-2",
                                    "at_black-t-3",
                                    "at_black-t-4",
                                    "at_black-t-5",
                                    "at_black-t-6"
                                ],
                                "isExpanded": false,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black-t-1",
                                "name": "black-t-1",
                                "raw": "var(--at-black-t-1)",
                                "rawValue": {
                                    "light": "hsla(0,0%,0%,0.84)",
                                    "dark": "hsla(0,0%,100%,0.84)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_black",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black-t-2",
                                "name": "black-t-2",
                                "raw": "var(--at-black-t-2)",
                                "rawValue": {
                                    "light": "hsla(0,0%,0%,0.68)",
                                    "dark": "hsla(0,0%,100%,0.68)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_black",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black-t-3",
                                "name": "black-t-3",
                                "raw": "var(--at-black-t-3)",
                                "rawValue": {
                                    "light": "hsla(0,0%,0%,0.53)",
                                    "dark": "hsla(0,0%,100%,0.53)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_black",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black-t-4",
                                "name": "black-t-4",
                                "raw": "var(--at-black-t-4)",
                                "rawValue": {
                                    "light": "hsla(0,0%,0%,0.37)",
                                    "dark": "hsla(0,0%,100%,0.37)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_black",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black-t-5",
                                "name": "black-t-5",
                                "raw": "var(--at-black-t-5)",
                                "rawValue": {
                                    "light": "hsla(0,0%,0%,0.21)",
                                    "dark": "hsla(0,0%,100%,0.21)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_black",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black-t-6",
                                "name": "black-t-6",
                                "raw": "var(--at-black-t-6)",
                                "rawValue": {
                                    "light": "hsla(0,0%,0%,0.05)",
                                    "dark": "hsla(0,0%,100%,0.05)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_black",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white",
                                "name": "white",
                                "raw": "var(--at-white)",
                                "rawValue": {
                                    "light": "#ffffff",
                                    "dark": "#000000"
                                },
                                "complementaryChildren": [],
                                "shadeChildren": [
                                    "at_white-t-1",
                                    "at_white-t-2",
                                    "at_white-t-3",
                                    "at_white-t-4",
                                    "at_white-t-5",
                                    "at_white-t-6"
                                ],
                                "isExpanded": false,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white-t-1",
                                "name": "white-t-1",
                                "raw": "var(--at-white-t-1)",
                                "rawValue": {
                                    "light": "hsla(0,0%,100%,0.84)",
                                    "dark": "hsla(0,0%,0%,0.84)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_white",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white-t-2",
                                "name": "white-t-2",
                                "raw": "var(--at-white-t-2)",
                                "rawValue": {
                                    "light": "hsla(0,0%,100%,0.68)",
                                    "dark": "hsla(0,0%,0%,0.68)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_white",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white-t-3",
                                "name": "white-t-3",
                                "raw": "var(--at-white-t-3)",
                                "rawValue": {
                                    "light": "hsla(0,0%,100%,0.53)",
                                    "dark": "hsla(0,0%,0%,0.53)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_white",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white-t-4",
                                "name": "white-t-4",
                                "raw": "var(--at-white-t-4)",
                                "rawValue": {
                                    "light": "hsla(0,0%,100%,0.37)",
                                    "dark": "hsla(0,0%,0%,0.37)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_white",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white-t-5",
                                "name": "white-t-5",
                                "raw": "var(--at-white-t-5)",
                                "rawValue": {
                                    "light": "hsla(0,0%,100%,0.21)",
                                    "dark": "hsla(0,0%,0%,0.21)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_white",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white-t-6",
                                "name": "white-t-6",
                                "raw": "var(--at-white-t-6)",
                                "rawValue": {
                                    "light": "hsla(0,0%,100%,0.05)",
                                    "dark": "hsla(0,0%,0%,0.05)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_white",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            }
                        ]
                    },
                    {
                        "id": "at_contextual_palette",
                        "name": "AT Contextual",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "status": "enabled",
                        "prefix": "at-",
                        "colors": [
                            {
                                "id": "at_contextual_info",
                                "name": "info",
                                "raw": "var(--at-info)",
                                "rawValue": {
                                    "light": "hsla(210, 76%, 54%, 1)",
                                    "dark": "hsla(210, 76%, 54%, 1)"
                                },
                                "complementaryChildren": [],
                                "shadeChildren": [],
                                "isExpanded": true,
                                "at_framework": true,
                                "at_version": "1.1.0"
                            },
                            {
                                "id": "at_contextual_success",
                                "name": "success",
                                "raw": "var(--at-success)",
                                "rawValue": {
                                    "light": "hsla(124, 58%, 57%, 1)",
                                    "dark": "hsla(124, 58%, 57%, 1)"
                                },
                                "complementaryChildren": [],
                                "shadeChildren": [],
                                "isExpanded": true,
                                "at_framework": true,
                                "at_version": "1.1.0"
                            },
                            {
                                "id": "at_contextual_warning",
                                "name": "warning",
                                "raw": "var(--at-warning)",
                                "rawValue": {
                                    "light": "hsla(29, 62%, 63%, 1)",
                                    "dark": "hsla(29, 62%, 63%, 1)"
                                },
                                "complementaryChildren": [],
                                "shadeChildren": [],
                                "isExpanded": true,
                                "at_framework": true,
                                "at_version": "1.1.0"
                            },
                            {
                                "id": "at_contextual_danger",
                                "name": "danger",
                                "raw": "var(--at-danger)",
                                "rawValue": {
                                    "light": "hsla(0, 75%, 61%, 1)",
                                    "dark": "hsla(0, 75%, 61%, 1)"
                                },
                                "complementaryChildren": [],
                                "shadeChildren": [],
                                "isExpanded": true,
                                "at_framework": true,
                                "at_version": "1.1.0"
                            }
                        ]
                    },
                    {
                        "id": "at_framework_palette_green",
                        "name": "AT Green",
                        "at_framework": true,
                        "at_version": "1.0.0",
                        "status": "disabled",
                        "prefix": "at-",
                        "defaultExpanded": true,
                        "colors": [
                            {
                                "id": "at_primary-green",
                                "name": "primary",
                                "raw": "var(--at-primary)",
                                "rawValue": {
                                    "light": "hsla(141, 74.3%, 51.2%, 1)",
                                    "dark": "hsla(141, 74.3%, 51.2%, 1)"
                                },
                                "complementaryChildren": [],
                                "shadeChildren": [
                                    "at_primary-l-1-green",
                                    "at_primary-l-2-green",
                                    "at_primary-l-3-green",
                                    "at_primary-l-4-green",
                                    "at_primary-l-5-green",
                                    "at_primary-l-6-green",
                                    "at_primary-d-1-green",
                                    "at_primary-d-2-green",
                                    "at_primary-d-3-green",
                                    "at_primary-d-4-green",
                                    "at_primary-d-5-green",
                                    "at_primary-d-6-green",
                                    "at_primary-t-1-green",
                                    "at_primary-t-2-green",
                                    "at_primary-t-3-green",
                                    "at_primary-t-4-green",
                                    "at_primary-t-5-green",
                                    "at_primary-t-6-green"
                                ],
                                "isExpanded": false,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-l-1-green",
                                "name": "primary-l-1",
                                "raw": "var(--at-primary-l-1)",
                                "rawValue": {
                                    "light": "hsla(140.9,74.16%,59.02%,1)",
                                    "dark": "hsla(140.9,74.16%,40.98%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_primary-green",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-l-2-green",
                                "name": "primary-l-2",
                                "raw": "var(--at-primary-l-2)",
                                "rawValue": {
                                    "light": "hsla(141.43,74.12%,66.67%,1)",
                                    "dark": "hsla(141.43,74.12%,33.33%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_primary-green",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-l-3-green",
                                "name": "primary-l-3",
                                "raw": "var(--at-primary-l-3)",
                                "rawValue": {
                                    "light": "hsla(141.03,75.19%,74.71%,1)",
                                    "dark": "hsla(141.03,75.19%,25.29%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_primary-green",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-l-4-green",
                                "name": "primary-l-4",
                                "raw": "var(--at-primary-l-4)",
                                "rawValue": {
                                    "light": "hsla(140.6,75.28%,82.55%,1)",
                                    "dark": "hsla(140.6,75.28%,17.45%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_primary-green",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-l-5-green",
                                "name": "primary-l-5",
                                "raw": "var(--at-primary-l-5)",
                                "rawValue": {
                                    "light": "hsla(142.11,76%,90.2%,1)",
                                    "dark": "hsla(142.11,76%,9.8%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_primary-green",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-l-6-green",
                                "name": "primary-l-6",
                                "raw": "var(--at-primary-l-6)",
                                "rawValue": {
                                    "light": "hsla(142.5,80%,98.04%,1)",
                                    "dark": "hsla(142.5,80%,1.96%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_primary-green",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-d-1-green",
                                "name": "primary-d-1",
                                "raw": "var(--at-primary-d-1)",
                                "rawValue": {
                                    "light": "hsla(141.27,71.17%,43.53%,1)",
                                    "dark": "hsla(141.27,71.17%,56.47%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_primary-green",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-d-2-green",
                                "name": "primary-d-2",
                                "raw": "var(--at-primary-d-2)",
                                "rawValue": {
                                    "light": "hsla(141.23,71.43%,35.69%,1)",
                                    "dark": "hsla(141.23,71.43%,64.31%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_primary-green",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-d-3-green",
                                "name": "primary-d-3",
                                "raw": "var(--at-primary-d-3)",
                                "rawValue": {
                                    "light": "hsla(141.18,70.83%,28.24%,1)",
                                    "dark": "hsla(141.18,70.83%,71.76%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_primary-green",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-d-4-green",
                                "name": "primary-d-4",
                                "raw": "var(--at-primary-d-4)",
                                "rawValue": {
                                    "light": "hsla(141.08,71.15%,20.39%,1)",
                                    "dark": "hsla(141.08,71.15%,79.61%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_primary-green",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-d-5-green",
                                "name": "primary-d-5",
                                "raw": "var(--at-primary-d-5)",
                                "rawValue": {
                                    "light": "hsla(141.7,72.31%,12.75%,1)",
                                    "dark": "hsla(141.7,72.31%,87.25%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_primary-green",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-d-6-green",
                                "name": "primary-d-6",
                                "raw": "var(--at-primary-d-6)",
                                "rawValue": {
                                    "light": "hsla(142.11,76%,4.9%,1)",
                                    "dark": "hsla(142.11,76%,95.1%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_primary-green",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-t-1-green",
                                "name": "primary-t-1",
                                "raw": "var(--at-primary-t-1)",
                                "rawValue": {
                                    "light": "hsla(141.08,74.3%,51.18%,0.84)",
                                    "dark": "hsla(141.08,74.3%,48.82%,0.84)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_primary-green",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-t-2-green",
                                "name": "primary-t-2",
                                "raw": "var(--at-primary-t-2)",
                                "rawValue": {
                                    "light": "hsla(141.08,74.3%,51.18%,0.68)",
                                    "dark": "hsla(141.08,74.3%,48.82%,0.68)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_primary-green",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-t-3-green",
                                "name": "primary-t-3",
                                "raw": "var(--at-primary-t-3)",
                                "rawValue": {
                                    "light": "hsla(141.08,74.3%,51.18%,0.53)",
                                    "dark": "hsla(141.08,74.3%,48.82%,0.53)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_primary-green",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-t-4-green",
                                "name": "primary-t-4",
                                "raw": "var(--at-primary-t-4)",
                                "rawValue": {
                                    "light": "hsla(141.08,74.3%,51.18%,0.37)",
                                    "dark": "hsla(141.08,74.3%,48.82%,0.37)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_primary-green",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-t-5-green",
                                "name": "primary-t-5",
                                "raw": "var(--at-primary-t-5)",
                                "rawValue": {
                                    "light": "hsla(141.08,74.3%,51.18%,0.21)",
                                    "dark": "hsla(141.08,74.3%,48.82%,0.21)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_primary-green",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-t-6-green",
                                "name": "primary-t-6",
                                "raw": "var(--at-primary-t-6)",
                                "rawValue": {
                                    "light": "hsla(141.08,74.3%,51.18%,0.05)",
                                    "dark": "hsla(141.08,74.3%,48.82%,0.05)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_primary-green",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-green",
                                "name": "secondary",
                                "raw": "var(--at-secondary)",
                                "rawValue": {
                                    "light": "hsla(186, 65%, 55%, 1)",
                                    "dark": "hsla(186, 65%, 55%, 1)"
                                },
                                "complementaryChildren": [],
                                "shadeChildren": [
                                    "at_secondary-l-1-green",
                                    "at_secondary-l-2-green",
                                    "at_secondary-l-3-green",
                                    "at_secondary-l-4-green",
                                    "at_secondary-l-5-green",
                                    "at_secondary-l-6-green",
                                    "at_secondary-d-1-green",
                                    "at_secondary-d-2-green",
                                    "at_secondary-d-3-green",
                                    "at_secondary-d-4-green",
                                    "at_secondary-d-5-green",
                                    "at_secondary-d-6-green",
                                    "at_secondary-t-1-green",
                                    "at_secondary-t-2-green",
                                    "at_secondary-t-3-green",
                                    "at_secondary-t-4-green",
                                    "at_secondary-t-5-green",
                                    "at_secondary-t-6-green"
                                ],
                                "isExpanded": false,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-l-1-green",
                                "name": "secondary-l-1",
                                "raw": "var(--at-secondary-l-1)",
                                "rawValue": {
                                    "light": "hsla(185.76,64.77%,62.16%,1)",
                                    "dark": "hsla(185.76,64.77%,37.84%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_secondary-green",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-l-2-green",
                                "name": "secondary-l-2",
                                "raw": "var(--at-secondary-l-2)",
                                "rawValue": {
                                    "light": "hsla(185.88,65.38%,69.41%,1)",
                                    "dark": "hsla(185.88,65.38%,30.59%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_secondary-green",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-l-3-green",
                                "name": "secondary-l-3",
                                "raw": "var(--at-secondary-l-3)",
                                "rawValue": {
                                    "light": "hsla(185.45,64.71%,76.67%,1)",
                                    "dark": "hsla(185.45,64.71%,23.33%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_secondary-green",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-l-4-green",
                                "name": "secondary-l-4",
                                "raw": "var(--at-secondary-l-4)",
                                "rawValue": {
                                    "light": "hsla(185.66,63.86%,83.73%,1)",
                                    "dark": "hsla(185.66,63.86%,16.27%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_secondary-green",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-l-5-green",
                                "name": "secondary-l-5",
                                "raw": "var(--at-secondary-l-5)",
                                "rawValue": {
                                    "light": "hsla(186,65.22%,90.98%,1)",
                                    "dark": "hsla(186,65.22%,9.02%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_secondary-green",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-l-6-green",
                                "name": "secondary-l-6",
                                "raw": "var(--at-secondary-l-6)",
                                "rawValue": {
                                    "light": "hsla(180,60%,98.04%,1)",
                                    "dark": "hsla(180,60%,1.96%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_secondary-green",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-d-1-green",
                                "name": "secondary-d-1",
                                "raw": "var(--at-secondary-d-1)",
                                "rawValue": {
                                    "light": "hsla(186.14,53.14%,46.86%,1)",
                                    "dark": "hsla(186.14,53.14%,53.14%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_secondary-green",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-d-2-green",
                                "name": "secondary-d-2",
                                "raw": "var(--at-secondary-d-2)",
                                "rawValue": {
                                    "light": "hsla(185.71,53.85%,38.24%,1)",
                                    "dark": "hsla(185.71,53.85%,61.76%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_secondary-green",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-d-3-green",
                                "name": "secondary-d-3",
                                "raw": "var(--at-secondary-d-3)",
                                "rawValue": {
                                    "light": "hsla(185.78,54.25%,30%,1)",
                                    "dark": "hsla(185.78,54.25%,70%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_secondary-green",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-d-4-green",
                                "name": "secondary-d-4",
                                "raw": "var(--at-secondary-d-4)",
                                "rawValue": {
                                    "light": "hsla(186.89,54.95%,21.76%,1)",
                                    "dark": "hsla(186.89,54.95%,78.24%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_secondary-green",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-d-5-green",
                                "name": "secondary-d-5",
                                "raw": "var(--at-secondary-d-5)",
                                "rawValue": {
                                    "light": "hsla(186.15,58.21%,13.14%,1)",
                                    "dark": "hsla(186.15,58.21%,86.86%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_secondary-green",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-d-6-green",
                                "name": "secondary-d-6",
                                "raw": "var(--at-secondary-d-6)",
                                "rawValue": {
                                    "light": "hsla(187.06,68%,4.9%,1)",
                                    "dark": "hsla(187.06,68%,95.1%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_secondary-green",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-t-1-green",
                                "name": "secondary-t-1",
                                "raw": "var(--at-secondary-t-1)",
                                "rawValue": {
                                    "light": "hsla(186.04,65.07%,55.1%,0.84)",
                                    "dark": "hsla(186.04,65.07%,44.9%,0.84)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_secondary-green",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-t-2-green",
                                "name": "secondary-t-2",
                                "raw": "var(--at-secondary-t-2)",
                                "rawValue": {
                                    "light": "hsla(186.04,65.07%,55.1%,0.68)",
                                    "dark": "hsla(186.04,65.07%,44.9%,0.68)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_secondary-green",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-t-3-green",
                                "name": "secondary-t-3",
                                "raw": "var(--at-secondary-t-3)",
                                "rawValue": {
                                    "light": "hsla(186.04,65.07%,55.1%,0.53)",
                                    "dark": "hsla(186.04,65.07%,44.9%,0.53)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_secondary-green",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-t-4-green",
                                "name": "secondary-t-4",
                                "raw": "var(--at-secondary-t-4)",
                                "rawValue": {
                                    "light": "hsla(186.04,65.07%,55.1%,0.37)",
                                    "dark": "hsla(186.04,65.07%,44.9%,0.37)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_secondary-green",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-t-5-green",
                                "name": "secondary-t-5",
                                "raw": "var(--at-secondary-t-5)",
                                "rawValue": {
                                    "light": "hsla(186.04,65.07%,55.1%,0.21)",
                                    "dark": "hsla(186.04,65.07%,44.9%,0.21)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_secondary-green",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-t-6-green",
                                "name": "secondary-t-6",
                                "raw": "var(--at-secondary-t-6)",
                                "rawValue": {
                                    "light": "hsla(186.04,65.07%,55.1%,0.05)",
                                    "dark": "hsla(186.04,65.07%,44.9%,0.05)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_secondary-green",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-green",
                                "name": "neutral",
                                "raw": "var(--at-neutral)",
                                "rawValue": {
                                    "light": "hsla(141, 20%, 50%, 1)",
                                    "dark": "hsla(141, 20%, 50%, 1)"
                                },
                                "complementaryChildren": [],
                                "shadeChildren": [
                                    "at_neutral-l-1-green",
                                    "at_neutral-l-2-green",
                                    "at_neutral-l-3-green",
                                    "at_neutral-l-4-green",
                                    "at_neutral-l-5-green",
                                    "at_neutral-l-6-green",
                                    "at_neutral-d-1-green",
                                    "at_neutral-d-2-green",
                                    "at_neutral-d-3-green",
                                    "at_neutral-d-4-green",
                                    "at_neutral-d-5-green",
                                    "at_neutral-d-6-green",
                                    "at_neutral-t-1-green",
                                    "at_neutral-t-2-green",
                                    "at_neutral-t-3-green",
                                    "at_neutral-t-4-green",
                                    "at_neutral-t-5-green",
                                    "at_neutral-t-6-green"
                                ],
                                "isExpanded": false,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-l-1-green",
                                "name": "neutral-l-1",
                                "raw": "var(--at-neutral-l-1)",
                                "rawValue": {
                                    "light": "hsla(141.43,19.63%,58.04%,1)",
                                    "dark": "hsla(141.43,19.63%,41.96%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_neutral-green",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-l-2-green",
                                "name": "neutral-l-2",
                                "raw": "var(--at-neutral-l-2)",
                                "rawValue": {
                                    "light": "hsla(140.57,20.23%,66.08%,1)",
                                    "dark": "hsla(140.57,20.23%,33.92%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_neutral-green",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-l-3-green",
                                "name": "neutral-l-3",
                                "raw": "var(--at-neutral-l-3)",
                                "rawValue": {
                                    "light": "hsla(140.77,19.7%,74.12%,1)",
                                    "dark": "hsla(140.77,19.7%,25.88%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_neutral-green",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-l-4-green",
                                "name": "neutral-l-4",
                                "raw": "var(--at-neutral-l-4)",
                                "rawValue": {
                                    "light": "hsla(143.33,19.57%,81.96%,1)",
                                    "dark": "hsla(143.33,19.57%,18.04%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_neutral-green",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-l-5-green",
                                "name": "neutral-l-5",
                                "raw": "var(--at-neutral-l-5)",
                                "rawValue": {
                                    "light": "hsla(138,20%,90.2%,1)",
                                    "dark": "hsla(138,20%,9.8%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_neutral-green",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-l-6-green",
                                "name": "neutral-l-6",
                                "raw": "var(--at-neutral-l-6)",
                                "rawValue": {
                                    "light": "hsla(150,20%,98.04%,1)",
                                    "dark": "hsla(150,20%,1.96%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_neutral-green",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-d-1-green",
                                "name": "neutral-d-1",
                                "raw": "var(--at-neutral-d-1)",
                                "rawValue": {
                                    "light": "hsla(140.93,19.82%,42.55%,1)",
                                    "dark": "hsla(140.93,19.82%,57.45%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_neutral-green",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-d-2-green",
                                "name": "neutral-d-2",
                                "raw": "var(--at-neutral-d-2)",
                                "rawValue": {
                                    "light": "hsla(141.67,20.22%,34.9%,1)",
                                    "dark": "hsla(141.67,20.22%,65.1%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_neutral-green",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-d-3-green",
                                "name": "neutral-d-3",
                                "raw": "var(--at-neutral-d-3)",
                                "rawValue": {
                                    "light": "hsla(141.43,20%,27.45%,1)",
                                    "dark": "hsla(141.43,20%,72.55%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_neutral-green",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-d-4-green",
                                "name": "neutral-d-4",
                                "raw": "var(--at-neutral-d-4)",
                                "rawValue": {
                                    "light": "hsla(141,19.61%,20%,1)",
                                    "dark": "hsla(141,19.61%,80%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_neutral-green",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-d-5-green",
                                "name": "neutral-d-5",
                                "raw": "var(--at-neutral-d-5)",
                                "rawValue": {
                                    "light": "hsla(143.08,20.63%,12.35%,1)",
                                    "dark": "hsla(143.08,20.63%,87.65%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_neutral-green",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-d-6-green",
                                "name": "neutral-d-6",
                                "raw": "var(--at-neutral-d-6)",
                                "rawValue": {
                                    "light": "hsla(144,20%,4.9%,1)",
                                    "dark": "hsla(144,20%,95.1%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_neutral-green",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-t-1-green",
                                "name": "neutral-t-1",
                                "raw": "var(--at-neutral-t-1)",
                                "rawValue": {
                                    "light": "hsla(141.18,20%,50%,0.84)",
                                    "dark": "hsla(141.18,20%,50%,0.84)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_neutral-green",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-t-2-green",
                                "name": "neutral-t-2",
                                "raw": "var(--at-neutral-t-2)",
                                "rawValue": {
                                    "light": "hsla(141.18,20%,50%,0.68)",
                                    "dark": "hsla(141.18,20%,50%,0.68)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_neutral-green",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-t-3-green",
                                "name": "neutral-t-3",
                                "raw": "var(--at-neutral-t-3)",
                                "rawValue": {
                                    "light": "hsla(141.18,20%,50%,0.53)",
                                    "dark": "hsla(141.18,20%,50%,0.53)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_neutral-green",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-t-4-green",
                                "name": "neutral-t-4",
                                "raw": "var(--at-neutral-t-4)",
                                "rawValue": {
                                    "light": "hsla(141.18,20%,50%,0.37)",
                                    "dark": "hsla(141.18,20%,50%,0.37)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_neutral-green",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-t-5-green",
                                "name": "neutral-t-5",
                                "raw": "var(--at-neutral-t-5)",
                                "rawValue": {
                                    "light": "hsla(141.18,20%,50%,0.21)",
                                    "dark": "hsla(141.18,20%,50%,0.21)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_neutral-green",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-t-6-green",
                                "name": "neutral-t-6",
                                "raw": "var(--at-neutral-t-6)",
                                "rawValue": {
                                    "light": "hsla(141.18,20%,50%,0.05)",
                                    "dark": "hsla(141.18,20%,50%,0.05)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_neutral-green",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black-green",
                                "name": "black",
                                "raw": "var(--at-black)",
                                "rawValue": {
                                    "light": "hsla(0, 0%, 0%, 1)",
                                    "dark": "#ffffff"
                                },
                                "complementaryChildren": [],
                                "shadeChildren": [
                                    "at_black-t-1-green",
                                    "at_black-t-2-green",
                                    "at_black-t-3-green",
                                    "at_black-t-4-green",
                                    "at_black-t-5-green",
                                    "at_black-t-6-green"
                                ],
                                "isExpanded": false,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black-t-1-green",
                                "name": "black-t-1",
                                "raw": "var(--at-black-t-1)",
                                "rawValue": {
                                    "light": "hsla(0,0%,0%,0.84)",
                                    "dark": "hsla(0,0%,100%,0.84)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_black-green",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black-t-2-green",
                                "name": "black-t-2",
                                "raw": "var(--at-black-t-2)",
                                "rawValue": {
                                    "light": "hsla(0,0%,0%,0.68)",
                                    "dark": "hsla(0,0%,100%,0.68)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_black-green",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black-t-3-green",
                                "name": "black-t-3",
                                "raw": "var(--at-black-t-3)",
                                "rawValue": {
                                    "light": "hsla(0,0%,0%,0.53)",
                                    "dark": "hsla(0,0%,100%,0.53)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_black-green",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black-t-4-green",
                                "name": "black-t-4",
                                "raw": "var(--at-black-t-4)",
                                "rawValue": {
                                    "light": "hsla(0,0%,0%,0.37)",
                                    "dark": "hsla(0,0%,100%,0.37)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_black-green",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black-t-5-green",
                                "name": "black-t-5",
                                "raw": "var(--at-black-t-5)",
                                "rawValue": {
                                    "light": "hsla(0,0%,0%,0.21)",
                                    "dark": "hsla(0,0%,100%,0.21)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_black-green",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black-t-6-green",
                                "name": "black-t-6",
                                "raw": "var(--at-black-t-6)",
                                "rawValue": {
                                    "light": "hsla(0,0%,0%,0.05)",
                                    "dark": "hsla(0,0%,100%,0.05)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_black-green",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white-green",
                                "name": "white",
                                "raw": "var(--at-white)",
                                "rawValue": {
                                    "light": "#ffffff",
                                    "dark": "#000000"
                                },
                                "complementaryChildren": [],
                                "shadeChildren": [
                                    "at_white-t-1-green",
                                    "at_white-t-2-green",
                                    "at_white-t-3-green",
                                    "at_white-t-4-green",
                                    "at_white-t-5-green",
                                    "at_white-t-6-green"
                                ],
                                "isExpanded": false,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white-t-1-green",
                                "name": "white-t-1",
                                "raw": "var(--at-white-t-1)",
                                "rawValue": {
                                    "light": "hsla(0,0%,100%,0.84)",
                                    "dark": "hsla(0,0%,0%,0.84)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_white-green",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white-t-2-green",
                                "name": "white-t-2",
                                "raw": "var(--at-white-t-2)",
                                "rawValue": {
                                    "light": "hsla(0,0%,100%,0.68)",
                                    "dark": "hsla(0,0%,0%,0.68)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_white-green",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white-t-3-green",
                                "name": "white-t-3",
                                "raw": "var(--at-white-t-3)",
                                "rawValue": {
                                    "light": "hsla(0,0%,100%,0.53)",
                                    "dark": "hsla(0,0%,0%,0.53)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_white-green",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white-t-4-green",
                                "name": "white-t-4",
                                "raw": "var(--at-white-t-4)",
                                "rawValue": {
                                    "light": "hsla(0,0%,100%,0.37)",
                                    "dark": "hsla(0,0%,0%,0.37)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_white-green",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white-t-5-green",
                                "name": "white-t-5",
                                "raw": "var(--at-white-t-5)",
                                "rawValue": {
                                    "light": "hsla(0,0%,100%,0.21)",
                                    "dark": "hsla(0,0%,0%,0.21)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_white-green",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white-t-6-green",
                                "name": "white-t-6",
                                "raw": "var(--at-white-t-6)",
                                "rawValue": {
                                    "light": "hsla(0,0%,100%,0.05)",
                                    "dark": "hsla(0,0%,0%,0.05)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_white-green",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            }
                        ]
                    },
                    {
                        "id": "at_framework_palette_red",
                        "name": "AT Red",
                        "at_framework": true,
                        "at_version": "1.0.0",
                        "status": "disabled",
                        "prefix": "at-",
                        "defaultExpanded": true,
                        "colors": [
                            {
                                "id": "at_primary-red",
                                "name": "primary",
                                "raw": "var(--at-primary)",
                                "rawValue": {
                                    "light": "hsla(359, 81.7%, 45.1%, 1)",
                                    "dark": "hsla(359, 81.7%, 45.1%, 1)"
                                },
                                "complementaryChildren": [],
                                "shadeChildren": [
                                    "at_primary-l-1-red",
                                    "at_primary-l-2-red",
                                    "at_primary-l-3-red",
                                    "at_primary-l-4-red",
                                    "at_primary-l-5-red",
                                    "at_primary-l-6-red",
                                    "at_primary-d-1-red",
                                    "at_primary-d-2-red",
                                    "at_primary-d-3-red",
                                    "at_primary-d-4-red",
                                    "at_primary-d-5-red",
                                    "at_primary-d-6-red",
                                    "at_primary-t-1-red",
                                    "at_primary-t-2-red",
                                    "at_primary-t-3-red",
                                    "at_primary-t-4-red",
                                    "at_primary-t-5-red",
                                    "at_primary-t-6-red"
                                ],
                                "isExpanded": false,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-l-1-red",
                                "name": "primary-l-1",
                                "raw": "var(--at-primary-l-1)",
                                "rawValue": {
                                    "light": "hsla(359.24,67.52%,54.12%,1)",
                                    "dark": "hsla(359.24,67.52%,45.88%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_primary-red",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-l-2-red",
                                "name": "primary-l-2",
                                "raw": "var(--at-primary-l-2)",
                                "rawValue": {
                                    "light": "hsla(359.06,67.37%,62.75%,1)",
                                    "dark": "hsla(359.06,67.37%,37.25%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_primary-red",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-l-3-red",
                                "name": "primary-l-3",
                                "raw": "var(--at-primary-l-3)",
                                "rawValue": {
                                    "light": "hsla(359.39,68.06%,71.76%,1)",
                                    "dark": "hsla(359.39,68.06%,28.24%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_primary-red",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-l-4-red",
                                "name": "primary-l-4",
                                "raw": "var(--at-primary-l-4)",
                                "rawValue": {
                                    "light": "hsla(359.12,68%,80.39%,1)",
                                    "dark": "hsla(359.12,68%,19.61%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_primary-red",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-l-5-red",
                                "name": "primary-l-5",
                                "raw": "var(--at-primary-l-5)",
                                "rawValue": {
                                    "light": "hsla(0,70.37%,89.41%,1)",
                                    "dark": "hsla(0,70.37%,10.59%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_primary-red",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-l-6-red",
                                "name": "primary-l-6",
                                "raw": "var(--at-primary-l-6)",
                                "rawValue": {
                                    "light": "hsla(0,80%,98.04%,1)",
                                    "dark": "hsla(0,80%,1.96%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_primary-red",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-d-1-red",
                                "name": "primary-d-1",
                                "raw": "var(--at-primary-d-1)",
                                "rawValue": {
                                    "light": "hsla(358.88,81.63%,38.43%,1)",
                                    "dark": "hsla(358.88,81.63%,61.57%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_primary-red",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-d-2-red",
                                "name": "primary-d-2",
                                "raw": "var(--at-primary-d-2)",
                                "rawValue": {
                                    "light": "hsla(359.09,81.48%,31.76%,1)",
                                    "dark": "hsla(359.09,81.48%,68.24%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_primary-red",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-d-3-red",
                                "name": "primary-d-3",
                                "raw": "var(--at-primary-d-3)",
                                "rawValue": {
                                    "light": "hsla(358.85,81.25%,25.1%,1)",
                                    "dark": "hsla(358.85,81.25%,74.9%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_primary-red",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-d-4-red",
                                "name": "primary-d-4",
                                "raw": "var(--at-primary-d-4)",
                                "rawValue": {
                                    "light": "hsla(358.44,82.8%,18.24%,1)",
                                    "dark": "hsla(358.44,82.8%,81.76%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_primary-red",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-d-5-red",
                                "name": "primary-d-5",
                                "raw": "var(--at-primary-d-5)",
                                "rawValue": {
                                    "light": "hsla(357.55,83.05%,11.57%,1)",
                                    "dark": "hsla(357.55,83.05%,88.43%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_primary-red",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-d-6-red",
                                "name": "primary-d-6",
                                "raw": "var(--at-primary-d-6)",
                                "rawValue": {
                                    "light": "hsla(357.14,84%,4.9%,1)",
                                    "dark": "hsla(357.14,84%,95.1%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_primary-red",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-t-1-red",
                                "name": "primary-t-1",
                                "raw": "var(--at-primary-t-1)",
                                "rawValue": {
                                    "light": "hsla(359.04,81.74%,45.1%,0.84)",
                                    "dark": "hsla(359.04,81.74%,54.9%,0.84)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_primary-red",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-t-2-red",
                                "name": "primary-t-2",
                                "raw": "var(--at-primary-t-2)",
                                "rawValue": {
                                    "light": "hsla(359.04,81.74%,45.1%,0.68)",
                                    "dark": "hsla(359.04,81.74%,54.9%,0.68)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_primary-red",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-t-3-red",
                                "name": "primary-t-3",
                                "raw": "var(--at-primary-t-3)",
                                "rawValue": {
                                    "light": "hsla(359.04,81.74%,45.1%,0.53)",
                                    "dark": "hsla(359.04,81.74%,54.9%,0.53)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_primary-red",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-t-4-red",
                                "name": "primary-t-4",
                                "raw": "var(--at-primary-t-4)",
                                "rawValue": {
                                    "light": "hsla(359.04,81.74%,45.1%,0.37)",
                                    "dark": "hsla(359.04,81.74%,54.9%,0.37)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_primary-red",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-t-5-red",
                                "name": "primary-t-5",
                                "raw": "var(--at-primary-t-5)",
                                "rawValue": {
                                    "light": "hsla(359.04,81.74%,45.1%,0.21)",
                                    "dark": "hsla(359.04,81.74%,54.9%,0.21)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_primary-red",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-t-6-red",
                                "name": "primary-t-6",
                                "raw": "var(--at-primary-t-6)",
                                "rawValue": {
                                    "light": "hsla(359.04,81.74%,45.1%,0.05)",
                                    "dark": "hsla(359.04,81.74%,54.9%,0.05)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_primary-red",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-red",
                                "name": "secondary",
                                "raw": "var(--at-secondary)",
                                "rawValue": {
                                    "light": "hsla(18, 70%, 59%, 1)",
                                    "dark": "hsla(18, 70%, 59%, 1)"
                                },
                                "complementaryChildren": [],
                                "shadeChildren": [
                                    "at_secondary-l-1-red",
                                    "at_secondary-l-2-red",
                                    "at_secondary-l-3-red",
                                    "at_secondary-l-4-red",
                                    "at_secondary-l-5-red",
                                    "at_secondary-l-6-red",
                                    "at_secondary-d-1-red",
                                    "at_secondary-d-2-red",
                                    "at_secondary-d-3-red",
                                    "at_secondary-d-4-red",
                                    "at_secondary-d-5-red",
                                    "at_secondary-d-6-red",
                                    "at_secondary-t-1-red",
                                    "at_secondary-t-2-red",
                                    "at_secondary-t-3-red",
                                    "at_secondary-t-4-red",
                                    "at_secondary-t-5-red",
                                    "at_secondary-t-6-red"
                                ],
                                "isExpanded": false,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-l-1-red",
                                "name": "secondary-l-1",
                                "raw": "var(--at-secondary-l-1)",
                                "rawValue": {
                                    "light": "hsla(17.9,70.45%,65.49%,1)",
                                    "dark": "hsla(17.9,70.45%,34.51%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_secondary-red",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-l-2-red",
                                "name": "secondary-l-2",
                                "raw": "var(--at-secondary-l-2)",
                                "rawValue": {
                                    "light": "hsla(17.82,70.63%,71.96%,1)",
                                    "dark": "hsla(17.82,70.63%,28.04%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_secondary-red",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-l-3-red",
                                "name": "secondary-l-3",
                                "raw": "var(--at-secondary-l-3)",
                                "rawValue": {
                                    "light": "hsla(17.92,70.64%,78.63%,1)",
                                    "dark": "hsla(17.92,70.64%,21.37%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_secondary-red",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-l-4-red",
                                "name": "secondary-l-4",
                                "raw": "var(--at-secondary-l-4)",
                                "rawValue": {
                                    "light": "hsla(18.11,68.83%,84.9%,1)",
                                    "dark": "hsla(18.11,68.83%,15.1%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_secondary-red",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-l-5-red",
                                "name": "secondary-l-5",
                                "raw": "var(--at-secondary-l-5)",
                                "rawValue": {
                                    "light": "hsla(18,68.18%,91.37%,1)",
                                    "dark": "hsla(18,68.18%,8.63%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_secondary-red",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-l-6-red",
                                "name": "secondary-l-6",
                                "raw": "var(--at-secondary-l-6)",
                                "rawValue": {
                                    "light": "hsla(17.14,63.64%,97.84%,1)",
                                    "dark": "hsla(17.14,63.64%,2.16%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_secondary-red",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-d-1-red",
                                "name": "secondary-d-1",
                                "raw": "var(--at-secondary-d-1)",
                                "rawValue": {
                                    "light": "hsla(17.76,49.02%,50%,1)",
                                    "dark": "hsla(17.76,49.02%,50%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_secondary-red",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-d-2-red",
                                "name": "secondary-d-2",
                                "raw": "var(--at-secondary-d-2)",
                                "rawValue": {
                                    "light": "hsla(17.88,49.52%,41.18%,1)",
                                    "dark": "hsla(17.88,49.52%,58.82%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_secondary-red",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-d-3-red",
                                "name": "secondary-d-3",
                                "raw": "var(--at-secondary-d-3)",
                                "rawValue": {
                                    "light": "hsla(17.56,50%,32.16%,1)",
                                    "dark": "hsla(17.56,50%,67.84%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_secondary-red",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-d-4-red",
                                "name": "secondary-d-4",
                                "raw": "var(--at-secondary-d-4)",
                                "rawValue": {
                                    "light": "hsla(17.7,52.14%,22.94%,1)",
                                    "dark": "hsla(17.7,52.14%,77.06%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_secondary-red",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-d-5-red",
                                "name": "secondary-d-5",
                                "raw": "var(--at-secondary-d-5)",
                                "rawValue": {
                                    "light": "hsla(18,55.56%,14.12%,1)",
                                    "dark": "hsla(18,55.56%,85.88%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_secondary-red",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-d-6-red",
                                "name": "secondary-d-6",
                                "raw": "var(--at-secondary-d-6)",
                                "rawValue": {
                                    "light": "hsla(16.67,69.23%,5.1%,1)",
                                    "dark": "hsla(16.67,69.23%,94.9%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_secondary-red",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-t-1-red",
                                "name": "secondary-t-1",
                                "raw": "var(--at-secondary-t-1)",
                                "rawValue": {
                                    "light": "hsla(17.96,70.33%,59.02%,0.84)",
                                    "dark": "hsla(17.96,70.33%,40.98%,0.84)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_secondary-red",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-t-2-red",
                                "name": "secondary-t-2",
                                "raw": "var(--at-secondary-t-2)",
                                "rawValue": {
                                    "light": "hsla(17.96,70.33%,59.02%,0.68)",
                                    "dark": "hsla(17.96,70.33%,40.98%,0.68)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_secondary-red",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-t-3-red",
                                "name": "secondary-t-3",
                                "raw": "var(--at-secondary-t-3)",
                                "rawValue": {
                                    "light": "hsla(17.96,70.33%,59.02%,0.53)",
                                    "dark": "hsla(17.96,70.33%,40.98%,0.53)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_secondary-red",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-t-4-red",
                                "name": "secondary-t-4",
                                "raw": "var(--at-secondary-t-4)",
                                "rawValue": {
                                    "light": "hsla(17.96,70.33%,59.02%,0.37)",
                                    "dark": "hsla(17.96,70.33%,40.98%,0.37)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_secondary-red",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-t-5-red",
                                "name": "secondary-t-5",
                                "raw": "var(--at-secondary-t-5)",
                                "rawValue": {
                                    "light": "hsla(17.96,70.33%,59.02%,0.21)",
                                    "dark": "hsla(17.96,70.33%,40.98%,0.21)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_secondary-red",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-t-6-red",
                                "name": "secondary-t-6",
                                "raw": "var(--at-secondary-t-6)",
                                "rawValue": {
                                    "light": "hsla(17.96,70.33%,59.02%,0.05)",
                                    "dark": "hsla(17.96,70.33%,40.98%,0.05)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_secondary-red",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-red",
                                "name": "neutral",
                                "raw": "var(--at-neutral)",
                                "rawValue": {
                                    "light": "hsla(359, 7%, 50%, 1)",
                                    "dark": "hsla(359, 7%, 50%, 1)"
                                },
                                "complementaryChildren": [],
                                "shadeChildren": [
                                    "at_neutral-l-1-red",
                                    "at_neutral-l-2-red",
                                    "at_neutral-l-3-red",
                                    "at_neutral-l-4-red",
                                    "at_neutral-l-5-red",
                                    "at_neutral-l-6-red",
                                    "at_neutral-d-1-red",
                                    "at_neutral-d-2-red",
                                    "at_neutral-d-3-red",
                                    "at_neutral-d-4-red",
                                    "at_neutral-d-5-red",
                                    "at_neutral-d-6-red",
                                    "at_neutral-t-1-red",
                                    "at_neutral-t-2-red",
                                    "at_neutral-t-3-red",
                                    "at_neutral-t-4-red",
                                    "at_neutral-t-5-red",
                                    "at_neutral-t-6-red"
                                ],
                                "isExpanded": false,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-l-1-red",
                                "name": "neutral-l-1",
                                "raw": "var(--at-neutral-l-1)",
                                "rawValue": {
                                    "light": "hsla(0,6.54%,58.04%,1)",
                                    "dark": "hsla(0,6.54%,41.96%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_neutral-red",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-l-2-red",
                                "name": "neutral-l-2",
                                "raw": "var(--at-neutral-l-2)",
                                "rawValue": {
                                    "light": "hsla(0,6.36%,66.08%,1)",
                                    "dark": "hsla(0,6.36%,33.92%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_neutral-red",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-l-3-red",
                                "name": "neutral-l-3",
                                "raw": "var(--at-neutral-l-3)",
                                "rawValue": {
                                    "light": "hsla(0,6.06%,74.12%,1)",
                                    "dark": "hsla(0,6.06%,25.88%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_neutral-red",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-l-4-red",
                                "name": "neutral-l-4",
                                "raw": "var(--at-neutral-l-4)",
                                "rawValue": {
                                    "light": "hsla(0,6.52%,81.96%,1)",
                                    "dark": "hsla(0,6.52%,18.04%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_neutral-red",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-l-5-red",
                                "name": "neutral-l-5",
                                "raw": "var(--at-neutral-l-5)",
                                "rawValue": {
                                    "light": "hsla(0,5.88%,90%,1)",
                                    "dark": "hsla(0,5.88%,10%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_neutral-red",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-l-6-red",
                                "name": "neutral-l-6",
                                "raw": "var(--at-neutral-l-6)",
                                "rawValue": {
                                    "light": "hsla(0,0%,98.04%,1)",
                                    "dark": "hsla(0,0%,1.96%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_neutral-red",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-d-1-red",
                                "name": "neutral-d-1",
                                "raw": "var(--at-neutral-d-1)",
                                "rawValue": {
                                    "light": "hsla(0,6.91%,42.55%,1)",
                                    "dark": "hsla(0,6.91%,57.45%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_neutral-red",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-d-2-red",
                                "name": "neutral-d-2",
                                "raw": "var(--at-neutral-d-2)",
                                "rawValue": {
                                    "light": "hsla(0,6.74%,34.9%,1)",
                                    "dark": "hsla(0,6.74%,65.1%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_neutral-red",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-d-3-red",
                                "name": "neutral-d-3",
                                "raw": "var(--at-neutral-d-3)",
                                "rawValue": {
                                    "light": "hsla(0,6.38%,27.65%,1)",
                                    "dark": "hsla(0,6.38%,72.35%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_neutral-red",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-d-4-red",
                                "name": "neutral-d-4",
                                "raw": "var(--at-neutral-d-4)",
                                "rawValue": {
                                    "light": "hsla(0,6.8%,20.2%,1)",
                                    "dark": "hsla(0,6.8%,79.8%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_neutral-red",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-d-5-red",
                                "name": "neutral-d-5",
                                "raw": "var(--at-neutral-d-5)",
                                "rawValue": {
                                    "light": "hsla(0,6.25%,12.55%,1)",
                                    "dark": "hsla(0,6.25%,87.45%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_neutral-red",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-d-6-red",
                                "name": "neutral-d-6",
                                "raw": "var(--at-neutral-d-6)",
                                "rawValue": {
                                    "light": "hsla(0,7.69%,5.1%,1)",
                                    "dark": "hsla(0,7.69%,94.9%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_neutral-red",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-t-1-red",
                                "name": "neutral-t-1",
                                "raw": "var(--at-neutral-t-1)",
                                "rawValue": {
                                    "light": "hsla(0,6.67%,50%,0.84)",
                                    "dark": "hsla(0,6.67%,50%,0.84)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_neutral-red",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-t-2-red",
                                "name": "neutral-t-2",
                                "raw": "var(--at-neutral-t-2)",
                                "rawValue": {
                                    "light": "hsla(0,6.67%,50%,0.68)",
                                    "dark": "hsla(0,6.67%,50%,0.68)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_neutral-red",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-t-3-red",
                                "name": "neutral-t-3",
                                "raw": "var(--at-neutral-t-3)",
                                "rawValue": {
                                    "light": "hsla(0,6.67%,50%,0.53)",
                                    "dark": "hsla(0,6.67%,50%,0.53)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_neutral-red",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-t-4-red",
                                "name": "neutral-t-4",
                                "raw": "var(--at-neutral-t-4)",
                                "rawValue": {
                                    "light": "hsla(0,6.67%,50%,0.37)",
                                    "dark": "hsla(0,6.67%,50%,0.37)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_neutral-red",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-t-5-red",
                                "name": "neutral-t-5",
                                "raw": "var(--at-neutral-t-5)",
                                "rawValue": {
                                    "light": "hsla(0,6.67%,50%,0.21)",
                                    "dark": "hsla(0,6.67%,50%,0.21)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_neutral-red",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-t-6-red",
                                "name": "neutral-t-6",
                                "raw": "var(--at-neutral-t-6)",
                                "rawValue": {
                                    "light": "hsla(0,6.67%,50%,0.05)",
                                    "dark": "hsla(0,6.67%,50%,0.05)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_neutral-red",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black-red",
                                "name": "black",
                                "raw": "var(--at-black)",
                                "rawValue": {
                                    "light": "hsla(0, 0%, 0%, 1)",
                                    "dark": "#ffffff"
                                },
                                "complementaryChildren": [],
                                "shadeChildren": [
                                    "at_black-t-1-red",
                                    "at_black-t-2-red",
                                    "at_black-t-3-red",
                                    "at_black-t-4-red",
                                    "at_black-t-5-red",
                                    "at_black-t-6-red"
                                ],
                                "isExpanded": false,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black-t-1-red",
                                "name": "black-t-1",
                                "raw": "var(--at-black-t-1)",
                                "rawValue": {
                                    "light": "hsla(0,0%,0%,0.84)",
                                    "dark": "hsla(0,0%,100%,0.84)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_black-red",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black-t-2-red",
                                "name": "black-t-2",
                                "raw": "var(--at-black-t-2)",
                                "rawValue": {
                                    "light": "hsla(0,0%,0%,0.68)",
                                    "dark": "hsla(0,0%,100%,0.68)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_black-red",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black-t-3-red",
                                "name": "black-t-3",
                                "raw": "var(--at-black-t-3)",
                                "rawValue": {
                                    "light": "hsla(0,0%,0%,0.53)",
                                    "dark": "hsla(0,0%,100%,0.53)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_black-red",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black-t-4-red",
                                "name": "black-t-4",
                                "raw": "var(--at-black-t-4)",
                                "rawValue": {
                                    "light": "hsla(0,0%,0%,0.37)",
                                    "dark": "hsla(0,0%,100%,0.37)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_black-red",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black-t-5-red",
                                "name": "black-t-5",
                                "raw": "var(--at-black-t-5)",
                                "rawValue": {
                                    "light": "hsla(0,0%,0%,0.21)",
                                    "dark": "hsla(0,0%,100%,0.21)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_black-red",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black-t-6-red",
                                "name": "black-t-6",
                                "raw": "var(--at-black-t-6)",
                                "rawValue": {
                                    "light": "hsla(0,0%,0%,0.05)",
                                    "dark": "hsla(0,0%,100%,0.05)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_black-red",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white-red",
                                "name": "white",
                                "raw": "var(--at-white)",
                                "rawValue": {
                                    "light": "#ffffff",
                                    "dark": "#000000"
                                },
                                "complementaryChildren": [],
                                "shadeChildren": [
                                    "at_white-t-1-red",
                                    "at_white-t-2-red",
                                    "at_white-t-3-red",
                                    "at_white-t-4-red",
                                    "at_white-t-5-red",
                                    "at_white-t-6-red"
                                ],
                                "isExpanded": false,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white-t-1-red",
                                "name": "white-t-1",
                                "raw": "var(--at-white-t-1)",
                                "rawValue": {
                                    "light": "hsla(0,0%,100%,0.84)",
                                    "dark": "hsla(0,0%,0%,0.84)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_white-red",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white-t-2-red",
                                "name": "white-t-2",
                                "raw": "var(--at-white-t-2)",
                                "rawValue": {
                                    "light": "hsla(0,0%,100%,0.68)",
                                    "dark": "hsla(0,0%,0%,0.68)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_white-red",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white-t-3-red",
                                "name": "white-t-3",
                                "raw": "var(--at-white-t-3)",
                                "rawValue": {
                                    "light": "hsla(0,0%,100%,0.53)",
                                    "dark": "hsla(0,0%,0%,0.53)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_white-red",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white-t-4-red",
                                "name": "white-t-4",
                                "raw": "var(--at-white-t-4)",
                                "rawValue": {
                                    "light": "hsla(0,0%,100%,0.37)",
                                    "dark": "hsla(0,0%,0%,0.37)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_white-red",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white-t-5-red",
                                "name": "white-t-5",
                                "raw": "var(--at-white-t-5)",
                                "rawValue": {
                                    "light": "hsla(0,0%,100%,0.21)",
                                    "dark": "hsla(0,0%,0%,0.21)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_white-red",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white-t-6-red",
                                "name": "white-t-6",
                                "raw": "var(--at-white-t-6)",
                                "rawValue": {
                                    "light": "hsla(0,0%,100%,0.05)",
                                    "dark": "hsla(0,0%,0%,0.05)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_white-red",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            }
                        ]
                    },
                    {
                        "id": "at_framework_palette_nature",
                        "name": "AT Nature",
                        "at_framework": true,
                        "at_version": "1.0.0",
                        "status": "disabled",
                        "prefix": "at-",
                        "defaultExpanded": true,
                        "colors": [
                            {
                                "id": "at_primary-nature",
                                "name": "primary",
                                "raw": "var(--at-primary)",
                                "rawValue": {
                                    "light": "hsla(29, 79.9%, 37.1%, 1)",
                                    "dark": "hsla(29, 79.9%, 37.1%, 1)"
                                },
                                "complementaryChildren": [],
                                "shadeChildren": [
                                    "at_primary-l-1-nature",
                                    "at_primary-l-2-nature",
                                    "at_primary-l-3-nature",
                                    "at_primary-l-4-nature",
                                    "at_primary-l-5-nature",
                                    "at_primary-l-6-nature",
                                    "at_primary-d-1-nature",
                                    "at_primary-d-2-nature",
                                    "at_primary-d-3-nature",
                                    "at_primary-d-4-nature",
                                    "at_primary-d-5-nature",
                                    "at_primary-d-6-nature",
                                    "at_primary-t-1-nature",
                                    "at_primary-t-2-nature",
                                    "at_primary-t-3-nature",
                                    "at_primary-t-4-nature",
                                    "at_primary-t-5-nature",
                                    "at_primary-t-6-nature"
                                ],
                                "isExpanded": false,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-l-1-nature",
                                "name": "primary-l-1",
                                "raw": "var(--at-primary-l-1)",
                                "rawValue": {
                                    "light": "hsla(28.82,52.7%,47.25%,1)",
                                    "dark": "hsla(28.82,52.7%,52.75%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_primary-nature",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-l-2-nature",
                                "name": "primary-l-2",
                                "raw": "var(--at-primary-l-2)",
                                "rawValue": {
                                    "light": "hsla(29.13,47.47%,57.45%,1)",
                                    "dark": "hsla(29.13,47.47%,42.55%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_primary-nature",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-l-3-nature",
                                "name": "primary-l-3",
                                "raw": "var(--at-primary-l-3)",
                                "rawValue": {
                                    "light": "hsla(28.86,47.88%,67.65%,1)",
                                    "dark": "hsla(28.86,47.88%,32.35%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_primary-nature",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-l-4-nature",
                                "name": "primary-l-4",
                                "raw": "var(--at-primary-l-4)",
                                "rawValue": {
                                    "light": "hsla(28.93,49.12%,77.65%,1)",
                                    "dark": "hsla(28.93,49.12%,22.35%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_primary-nature",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-l-5-nature",
                                "name": "primary-l-5",
                                "raw": "var(--at-primary-l-5)",
                                "rawValue": {
                                    "light": "hsla(30,51.61%,87.84%,1)",
                                    "dark": "hsla(30,51.61%,12.16%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_primary-nature",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-l-6-nature",
                                "name": "primary-l-6",
                                "raw": "var(--at-primary-l-6)",
                                "rawValue": {
                                    "light": "hsla(30,80%,98.04%,1)",
                                    "dark": "hsla(30,80%,1.96%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_primary-nature",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-d-1-nature",
                                "name": "primary-d-1",
                                "raw": "var(--at-primary-d-1)",
                                "rawValue": {
                                    "light": "hsla(29.08,80.25%,31.76%,1)",
                                    "dark": "hsla(29.08,80.25%,68.24%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_primary-nature",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-d-2-nature",
                                "name": "primary-d-2",
                                "raw": "var(--at-primary-d-2)",
                                "rawValue": {
                                    "light": "hsla(28.6,79.26%,26.47%,1)",
                                    "dark": "hsla(28.6,79.26%,73.53%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_primary-nature",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-d-3-nature",
                                "name": "primary-d-3",
                                "raw": "var(--at-primary-d-3)",
                                "rawValue": {
                                    "light": "hsla(28.6,79.63%,21.18%,1)",
                                    "dark": "hsla(28.6,79.63%,78.82%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_primary-nature",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-d-4-nature",
                                "name": "primary-d-4",
                                "raw": "var(--at-primary-d-4)",
                                "rawValue": {
                                    "light": "hsla(29.06,80%,15.69%,1)",
                                    "dark": "hsla(29.06,80%,84.31%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_primary-nature",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-d-5-nature",
                                "name": "primary-d-5",
                                "raw": "var(--at-primary-d-5)",
                                "rawValue": {
                                    "light": "hsla(27.14,77.78%,10.59%,1)",
                                    "dark": "hsla(27.14,77.78%,89.41%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_primary-nature",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-d-6-nature",
                                "name": "primary-d-6",
                                "raw": "var(--at-primary-d-6)",
                                "rawValue": {
                                    "light": "hsla(27,76.92%,5.1%,1)",
                                    "dark": "hsla(27,76.92%,94.9%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_primary-nature",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-t-1-nature",
                                "name": "primary-t-1",
                                "raw": "var(--at-primary-t-1)",
                                "rawValue": {
                                    "light": "hsla(29.01,79.89%,37.06%,0.84)",
                                    "dark": "hsla(29.01,79.89%,62.94%,0.84)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_primary-nature",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-t-2-nature",
                                "name": "primary-t-2",
                                "raw": "var(--at-primary-t-2)",
                                "rawValue": {
                                    "light": "hsla(29.01,79.89%,37.06%,0.68)",
                                    "dark": "hsla(29.01,79.89%,62.94%,0.68)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_primary-nature",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-t-3-nature",
                                "name": "primary-t-3",
                                "raw": "var(--at-primary-t-3)",
                                "rawValue": {
                                    "light": "hsla(29.01,79.89%,37.06%,0.53)",
                                    "dark": "hsla(29.01,79.89%,62.94%,0.53)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_primary-nature",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-t-4-nature",
                                "name": "primary-t-4",
                                "raw": "var(--at-primary-t-4)",
                                "rawValue": {
                                    "light": "hsla(29.01,79.89%,37.06%,0.37)",
                                    "dark": "hsla(29.01,79.89%,62.94%,0.37)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_primary-nature",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-t-5-nature",
                                "name": "primary-t-5",
                                "raw": "var(--at-primary-t-5)",
                                "rawValue": {
                                    "light": "hsla(29.01,79.89%,37.06%,0.21)",
                                    "dark": "hsla(29.01,79.89%,62.94%,0.21)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_primary-nature",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-t-6-nature",
                                "name": "primary-t-6",
                                "raw": "var(--at-primary-t-6)",
                                "rawValue": {
                                    "light": "hsla(29.01,79.89%,37.06%,0.05)",
                                    "dark": "hsla(29.01,79.89%,62.94%,0.05)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_primary-nature",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-nature",
                                "name": "secondary",
                                "raw": "var(--at-secondary)",
                                "rawValue": {
                                    "light": "hsla(86, 68%, 37%, 1)",
                                    "dark": "hsla(86, 68%, 37%, 1)"
                                },
                                "complementaryChildren": [],
                                "shadeChildren": [
                                    "at_secondary-l-1-nature",
                                    "at_secondary-l-2-nature",
                                    "at_secondary-l-3-nature",
                                    "at_secondary-l-4-nature",
                                    "at_secondary-l-5-nature",
                                    "at_secondary-l-6-nature",
                                    "at_secondary-d-1-nature",
                                    "at_secondary-d-2-nature",
                                    "at_secondary-d-3-nature",
                                    "at_secondary-d-4-nature",
                                    "at_secondary-d-5-nature",
                                    "at_secondary-d-6-nature",
                                    "at_secondary-t-1-nature",
                                    "at_secondary-t-2-nature",
                                    "at_secondary-t-3-nature",
                                    "at_secondary-t-4-nature",
                                    "at_secondary-t-5-nature",
                                    "at_secondary-t-6-nature"
                                ],
                                "isExpanded": false,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-l-1-nature",
                                "name": "secondary-l-1",
                                "raw": "var(--at-secondary-l-1)",
                                "rawValue": {
                                    "light": "hsla(85.87,45.23%,47.25%,1)",
                                    "dark": "hsla(85.87,45.23%,52.75%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_secondary-nature",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-l-2-nature",
                                "name": "secondary-l-2",
                                "raw": "var(--at-secondary-l-2)",
                                "rawValue": {
                                    "light": "hsla(85.91,40.37%,57.25%,1)",
                                    "dark": "hsla(85.91,40.37%,42.75%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_secondary-nature",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-l-3-nature",
                                "name": "secondary-l-3",
                                "raw": "var(--at-secondary-l-3)",
                                "rawValue": {
                                    "light": "hsla(85.59,40.96%,67.45%,1)",
                                    "dark": "hsla(85.59,40.96%,32.55%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_secondary-nature",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-l-4-nature",
                                "name": "secondary-l-4",
                                "raw": "var(--at-secondary-l-4)",
                                "rawValue": {
                                    "light": "hsla(86.25,42.11%,77.65%,1)",
                                    "dark": "hsla(86.25,42.11%,22.35%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_secondary-nature",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-l-5-nature",
                                "name": "secondary-l-5",
                                "raw": "var(--at-secondary-l-5)",
                                "rawValue": {
                                    "light": "hsla(84.44,42.86%,87.65%,1)",
                                    "dark": "hsla(84.44,42.86%,12.35%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_secondary-nature",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-l-6-nature",
                                "name": "secondary-l-6",
                                "raw": "var(--at-secondary-l-6)",
                                "rawValue": {
                                    "light": "hsla(85.71,63.64%,97.84%,1)",
                                    "dark": "hsla(85.71,63.64%,2.16%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_secondary-nature",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-d-1-nature",
                                "name": "secondary-d-1",
                                "raw": "var(--at-secondary-d-1)",
                                "rawValue": {
                                    "light": "hsla(86.18,67.9%,31.76%,1)",
                                    "dark": "hsla(86.18,67.9%,68.24%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_secondary-nature",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-d-2-nature",
                                "name": "secondary-d-2",
                                "raw": "var(--at-secondary-d-2)",
                                "rawValue": {
                                    "light": "hsla(86.09,68.66%,26.27%,1)",
                                    "dark": "hsla(86.09,68.66%,73.73%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_secondary-nature",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-d-3-nature",
                                "name": "secondary-d-3",
                                "raw": "var(--at-secondary-d-3)",
                                "rawValue": {
                                    "light": "hsla(85.48,68.22%,20.98%,1)",
                                    "dark": "hsla(85.48,68.22%,79.02%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_secondary-nature",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-d-4-nature",
                                "name": "secondary-d-4",
                                "raw": "var(--at-secondary-d-4)",
                                "rawValue": {
                                    "light": "hsla(85.56,67.5%,15.69%,1)",
                                    "dark": "hsla(85.56,67.5%,84.31%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_secondary-nature",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-d-5-nature",
                                "name": "secondary-d-5",
                                "raw": "var(--at-secondary-d-5)",
                                "rawValue": {
                                    "light": "hsla(85,69.23%,10.2%,1)",
                                    "dark": "hsla(85,69.23%,89.8%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_secondary-nature",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-d-6-nature",
                                "name": "secondary-d-6",
                                "raw": "var(--at-secondary-d-6)",
                                "rawValue": {
                                    "light": "hsla(84.71,68%,4.9%,1)",
                                    "dark": "hsla(84.71,68%,95.1%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_secondary-nature",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-t-1-nature",
                                "name": "secondary-t-1",
                                "raw": "var(--at-secondary-t-1)",
                                "rawValue": {
                                    "light": "hsla(86.05,68.25%,37.06%,0.84)",
                                    "dark": "hsla(86.05,68.25%,62.94%,0.84)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_secondary-nature",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-t-2-nature",
                                "name": "secondary-t-2",
                                "raw": "var(--at-secondary-t-2)",
                                "rawValue": {
                                    "light": "hsla(86.05,68.25%,37.06%,0.68)",
                                    "dark": "hsla(86.05,68.25%,62.94%,0.68)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_secondary-nature",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-t-3-nature",
                                "name": "secondary-t-3",
                                "raw": "var(--at-secondary-t-3)",
                                "rawValue": {
                                    "light": "hsla(86.05,68.25%,37.06%,0.53)",
                                    "dark": "hsla(86.05,68.25%,62.94%,0.53)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_secondary-nature",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-t-4-nature",
                                "name": "secondary-t-4",
                                "raw": "var(--at-secondary-t-4)",
                                "rawValue": {
                                    "light": "hsla(86.05,68.25%,37.06%,0.37)",
                                    "dark": "hsla(86.05,68.25%,62.94%,0.37)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_secondary-nature",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-t-5-nature",
                                "name": "secondary-t-5",
                                "raw": "var(--at-secondary-t-5)",
                                "rawValue": {
                                    "light": "hsla(86.05,68.25%,37.06%,0.21)",
                                    "dark": "hsla(86.05,68.25%,62.94%,0.21)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_secondary-nature",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-t-6-nature",
                                "name": "secondary-t-6",
                                "raw": "var(--at-secondary-t-6)",
                                "rawValue": {
                                    "light": "hsla(86.05,68.25%,37.06%,0.05)",
                                    "dark": "hsla(86.05,68.25%,62.94%,0.05)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_secondary-nature",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-nature",
                                "name": "neutral",
                                "raw": "var(--at-neutral)",
                                "rawValue": {
                                    "light": "hsla(29, 20%, 50%, 1)",
                                    "dark": "hsla(29, 20%, 50%, 1)"
                                },
                                "complementaryChildren": [],
                                "shadeChildren": [
                                    "at_neutral-l-1-nature",
                                    "at_neutral-l-2-nature",
                                    "at_neutral-l-3-nature",
                                    "at_neutral-l-4-nature",
                                    "at_neutral-l-5-nature",
                                    "at_neutral-l-6-nature",
                                    "at_neutral-d-1-nature",
                                    "at_neutral-d-2-nature",
                                    "at_neutral-d-3-nature",
                                    "at_neutral-d-4-nature",
                                    "at_neutral-d-5-nature",
                                    "at_neutral-d-6-nature",
                                    "at_neutral-t-1-nature",
                                    "at_neutral-t-2-nature",
                                    "at_neutral-t-3-nature",
                                    "at_neutral-t-4-nature",
                                    "at_neutral-t-5-nature",
                                    "at_neutral-t-6-nature"
                                ],
                                "isExpanded": false,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-l-1-nature",
                                "name": "neutral-l-1",
                                "raw": "var(--at-neutral-l-1)",
                                "rawValue": {
                                    "light": "hsla(30,19.63%,58.04%,1)",
                                    "dark": "hsla(30,19.63%,41.96%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_neutral-nature",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-l-2-nature",
                                "name": "neutral-l-2",
                                "raw": "var(--at-neutral-l-2)",
                                "rawValue": {
                                    "light": "hsla(29.14,20.23%,66.08%,1)",
                                    "dark": "hsla(29.14,20.23%,33.92%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_neutral-nature",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-l-3-nature",
                                "name": "neutral-l-3",
                                "raw": "var(--at-neutral-l-3)",
                                "rawValue": {
                                    "light": "hsla(30,19.7%,74.12%,1)",
                                    "dark": "hsla(30,19.7%,25.88%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_neutral-nature",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-l-4-nature",
                                "name": "neutral-l-4",
                                "raw": "var(--at-neutral-l-4)",
                                "rawValue": {
                                    "light": "hsla(30,19.57%,81.96%,1)",
                                    "dark": "hsla(30,19.57%,18.04%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_neutral-nature",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-l-5-nature",
                                "name": "neutral-l-5",
                                "raw": "var(--at-neutral-l-5)",
                                "rawValue": {
                                    "light": "hsla(30,20%,90.2%,1)",
                                    "dark": "hsla(30,20%,9.8%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_neutral-nature",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-l-6-nature",
                                "name": "neutral-l-6",
                                "raw": "var(--at-neutral-l-6)",
                                "rawValue": {
                                    "light": "hsla(30,20%,98.04%,1)",
                                    "dark": "hsla(30,20%,1.96%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_neutral-nature",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-d-1-nature",
                                "name": "neutral-d-1",
                                "raw": "var(--at-neutral-d-1)",
                                "rawValue": {
                                    "light": "hsla(29.3,19.82%,42.55%,1)",
                                    "dark": "hsla(29.3,19.82%,57.45%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_neutral-nature",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-d-2-nature",
                                "name": "neutral-d-2",
                                "raw": "var(--at-neutral-d-2)",
                                "rawValue": {
                                    "light": "hsla(30,20.22%,34.9%,1)",
                                    "dark": "hsla(30,20.22%,65.1%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_neutral-nature",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-d-3-nature",
                                "name": "neutral-d-3",
                                "raw": "var(--at-neutral-d-3)",
                                "rawValue": {
                                    "light": "hsla(30,20%,27.45%,1)",
                                    "dark": "hsla(30,20%,72.55%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_neutral-nature",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-d-4-nature",
                                "name": "neutral-d-4",
                                "raw": "var(--at-neutral-d-4)",
                                "rawValue": {
                                    "light": "hsla(30,19.61%,20%,1)",
                                    "dark": "hsla(30,19.61%,80%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_neutral-nature",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-d-5-nature",
                                "name": "neutral-d-5",
                                "raw": "var(--at-neutral-d-5)",
                                "rawValue": {
                                    "light": "hsla(32.31,20.63%,12.35%,1)",
                                    "dark": "hsla(32.31,20.63%,87.65%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_neutral-nature",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-d-6-nature",
                                "name": "neutral-d-6",
                                "raw": "var(--at-neutral-d-6)",
                                "rawValue": {
                                    "light": "hsla(36,20%,4.9%,1)",
                                    "dark": "hsla(36,20%,95.1%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_neutral-nature",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-t-1-nature",
                                "name": "neutral-t-1",
                                "raw": "var(--at-neutral-t-1)",
                                "rawValue": {
                                    "light": "hsla(29.41,20%,50%,0.84)",
                                    "dark": "hsla(29.41,20%,50%,0.84)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_neutral-nature",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-t-2-nature",
                                "name": "neutral-t-2",
                                "raw": "var(--at-neutral-t-2)",
                                "rawValue": {
                                    "light": "hsla(29.41,20%,50%,0.68)",
                                    "dark": "hsla(29.41,20%,50%,0.68)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_neutral-nature",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-t-3-nature",
                                "name": "neutral-t-3",
                                "raw": "var(--at-neutral-t-3)",
                                "rawValue": {
                                    "light": "hsla(29.41,20%,50%,0.53)",
                                    "dark": "hsla(29.41,20%,50%,0.53)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_neutral-nature",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-t-4-nature",
                                "name": "neutral-t-4",
                                "raw": "var(--at-neutral-t-4)",
                                "rawValue": {
                                    "light": "hsla(29.41,20%,50%,0.37)",
                                    "dark": "hsla(29.41,20%,50%,0.37)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_neutral-nature",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-t-5-nature",
                                "name": "neutral-t-5",
                                "raw": "var(--at-neutral-t-5)",
                                "rawValue": {
                                    "light": "hsla(29.41,20%,50%,0.21)",
                                    "dark": "hsla(29.41,20%,50%,0.21)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_neutral-nature",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-t-6-nature",
                                "name": "neutral-t-6",
                                "raw": "var(--at-neutral-t-6)",
                                "rawValue": {
                                    "light": "hsla(29.41,20%,50%,0.05)",
                                    "dark": "hsla(29.41,20%,50%,0.05)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_neutral-nature",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black-nature",
                                "name": "black",
                                "raw": "var(--at-black)",
                                "rawValue": {
                                    "light": "hsla(0, 0%, 0%, 1)",
                                    "dark": "#ffffff"
                                },
                                "complementaryChildren": [],
                                "shadeChildren": [
                                    "at_black-t-1-nature",
                                    "at_black-t-2-nature",
                                    "at_black-t-3-nature",
                                    "at_black-t-4-nature",
                                    "at_black-t-5-nature",
                                    "at_black-t-6-nature"
                                ],
                                "isExpanded": false,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black-t-1-nature",
                                "name": "black-t-1",
                                "raw": "var(--at-black-t-1)",
                                "rawValue": {
                                    "light": "hsla(0,0%,0%,0.84)",
                                    "dark": "hsla(0,0%,100%,0.84)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_black-nature",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black-t-2-nature",
                                "name": "black-t-2",
                                "raw": "var(--at-black-t-2)",
                                "rawValue": {
                                    "light": "hsla(0,0%,0%,0.68)",
                                    "dark": "hsla(0,0%,100%,0.68)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_black-nature",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black-t-3-nature",
                                "name": "black-t-3",
                                "raw": "var(--at-black-t-3)",
                                "rawValue": {
                                    "light": "hsla(0,0%,0%,0.53)",
                                    "dark": "hsla(0,0%,100%,0.53)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_black-nature",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black-t-4-nature",
                                "name": "black-t-4",
                                "raw": "var(--at-black-t-4)",
                                "rawValue": {
                                    "light": "hsla(0,0%,0%,0.37)",
                                    "dark": "hsla(0,0%,100%,0.37)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_black-nature",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black-t-5-nature",
                                "name": "black-t-5",
                                "raw": "var(--at-black-t-5)",
                                "rawValue": {
                                    "light": "hsla(0,0%,0%,0.21)",
                                    "dark": "hsla(0,0%,100%,0.21)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_black-nature",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black-t-6-nature",
                                "name": "black-t-6",
                                "raw": "var(--at-black-t-6)",
                                "rawValue": {
                                    "light": "hsla(0,0%,0%,0.05)",
                                    "dark": "hsla(0,0%,100%,0.05)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_black-nature",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white-nature",
                                "name": "white",
                                "raw": "var(--at-white)",
                                "rawValue": {
                                    "light": "#ffffff",
                                    "dark": "#000000"
                                },
                                "complementaryChildren": [],
                                "shadeChildren": [
                                    "at_white-t-1-nature",
                                    "at_white-t-2-nature",
                                    "at_white-t-3-nature",
                                    "at_white-t-4-nature",
                                    "at_white-t-5-nature",
                                    "at_white-t-6-nature"
                                ],
                                "isExpanded": false,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white-t-1-nature",
                                "name": "white-t-1",
                                "raw": "var(--at-white-t-1)",
                                "rawValue": {
                                    "light": "hsla(0,0%,100%,0.84)",
                                    "dark": "hsla(0,0%,0%,0.84)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_white-nature",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white-t-2-nature",
                                "name": "white-t-2",
                                "raw": "var(--at-white-t-2)",
                                "rawValue": {
                                    "light": "hsla(0,0%,100%,0.68)",
                                    "dark": "hsla(0,0%,0%,0.68)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_white-nature",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white-t-3-nature",
                                "name": "white-t-3",
                                "raw": "var(--at-white-t-3)",
                                "rawValue": {
                                    "light": "hsla(0,0%,100%,0.53)",
                                    "dark": "hsla(0,0%,0%,0.53)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_white-nature",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white-t-4-nature",
                                "name": "white-t-4",
                                "raw": "var(--at-white-t-4)",
                                "rawValue": {
                                    "light": "hsla(0,0%,100%,0.37)",
                                    "dark": "hsla(0,0%,0%,0.37)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_white-nature",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white-t-5-nature",
                                "name": "white-t-5",
                                "raw": "var(--at-white-t-5)",
                                "rawValue": {
                                    "light": "hsla(0,0%,100%,0.21)",
                                    "dark": "hsla(0,0%,0%,0.21)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_white-nature",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white-t-6-nature",
                                "name": "white-t-6",
                                "raw": "var(--at-white-t-6)",
                                "rawValue": {
                                    "light": "hsla(0,0%,100%,0.05)",
                                    "dark": "hsla(0,0%,0%,0.05)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_white-nature",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            }
                        ]
                    },
                    {
                        "id": "at_framework_palette_purple",
                        "name": "AT Purple",
                        "at_framework": true,
                        "at_version": "1.0.0",
                        "status": "disabled",
                        "prefix": "at-",
                        "defaultExpanded": true,
                        "colors": [
                            {
                                "id": "at_primary-purple",
                                "name": "primary",
                                "raw": "var(--at-primary)",
                                "rawValue": {
                                    "light": "hsla(301, 66.7%, 41.2%, 1)",
                                    "dark": "hsla(301, 66.7%, 41.2%, 1)"
                                },
                                "complementaryChildren": [],
                                "shadeChildren": [
                                    "at_primary-l-1-purple",
                                    "at_primary-l-2-purple",
                                    "at_primary-l-3-purple",
                                    "at_primary-l-4-purple",
                                    "at_primary-l-5-purple",
                                    "at_primary-l-6-purple",
                                    "at_primary-d-1-purple",
                                    "at_primary-d-2-purple",
                                    "at_primary-d-3-purple",
                                    "at_primary-d-4-purple",
                                    "at_primary-d-5-purple",
                                    "at_primary-d-6-purple",
                                    "at_primary-t-1-purple",
                                    "at_primary-t-2-purple",
                                    "at_primary-t-3-purple",
                                    "at_primary-t-4-purple",
                                    "at_primary-t-5-purple",
                                    "at_primary-t-6-purple"
                                ],
                                "isExpanded": false,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-l-1-purple",
                                "name": "primary-l-1",
                                "raw": "var(--at-primary-l-1)",
                                "rawValue": {
                                    "light": "hsla(301.02,46.83%,50.59%,1)",
                                    "dark": "hsla(301.02,46.83%,49.41%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_primary-purple",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-l-2-purple",
                                "name": "primary-l-2",
                                "raw": "var(--at-primary-l-2)",
                                "rawValue": {
                                    "light": "hsla(300.63,46.8%,60.2%,1)",
                                    "dark": "hsla(300.63,46.8%,39.8%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_primary-purple",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-l-3-purple",
                                "name": "primary-l-3",
                                "raw": "var(--at-primary-l-3)",
                                "rawValue": {
                                    "light": "hsla(300.82,47.1%,69.61%,1)",
                                    "dark": "hsla(300.82,47.1%,30.39%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_primary-purple",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-l-4-purple",
                                "name": "primary-l-4",
                                "raw": "var(--at-primary-l-4)",
                                "rawValue": {
                                    "light": "hsla(301.18,47.66%,79.02%,1)",
                                    "dark": "hsla(301.18,47.66%,20.98%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_primary-purple",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-l-5-purple",
                                "name": "primary-l-5",
                                "raw": "var(--at-primary-l-5)",
                                "rawValue": {
                                    "light": "hsla(300,48.28%,88.63%,1)",
                                    "dark": "hsla(300,48.28%,11.37%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_primary-purple",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-l-6-purple",
                                "name": "primary-l-6",
                                "raw": "var(--at-primary-l-6)",
                                "rawValue": {
                                    "light": "hsla(300,60%,98.04%,1)",
                                    "dark": "hsla(300,60%,1.96%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_primary-purple",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-d-1-purple",
                                "name": "primary-d-1",
                                "raw": "var(--at-primary-d-1)",
                                "rawValue": {
                                    "light": "hsla(300.5,66.48%,35.1%,1)",
                                    "dark": "hsla(300.5,66.48%,64.9%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_primary-purple",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-d-2-purple",
                                "name": "primary-d-2",
                                "raw": "var(--at-primary-d-2)",
                                "rawValue": {
                                    "light": "hsla(301.21,66.44%,29.22%,1)",
                                    "dark": "hsla(301.21,66.44%,70.78%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_primary-purple",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-d-3-purple",
                                "name": "primary-d-3",
                                "raw": "var(--at-primary-d-3)",
                                "rawValue": {
                                    "light": "hsla(300.77,66.1%,23.14%,1)",
                                    "dark": "hsla(300.77,66.1%,76.86%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_primary-purple",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-d-4-purple",
                                "name": "primary-d-4",
                                "raw": "var(--at-primary-d-4)",
                                "rawValue": {
                                    "light": "hsla(300,67.44%,16.86%,1)",
                                    "dark": "hsla(300,67.44%,83.14%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_primary-purple",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-d-5-purple",
                                "name": "primary-d-5",
                                "raw": "var(--at-primary-d-5)",
                                "rawValue": {
                                    "light": "hsla(301.58,67.86%,10.98%,1)",
                                    "dark": "hsla(301.58,67.86%,89.02%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_primary-purple",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-d-6-purple",
                                "name": "primary-d-6",
                                "raw": "var(--at-primary-d-6)",
                                "rawValue": {
                                    "light": "hsla(300,68%,4.9%,1)",
                                    "dark": "hsla(300,68%,95.1%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_primary-purple",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-t-1-purple",
                                "name": "primary-t-1",
                                "raw": "var(--at-primary-t-1)",
                                "rawValue": {
                                    "light": "hsla(300.86,66.67%,41.18%,0.84)",
                                    "dark": "hsla(300.86,66.67%,58.82%,0.84)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_primary-purple",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-t-2-purple",
                                "name": "primary-t-2",
                                "raw": "var(--at-primary-t-2)",
                                "rawValue": {
                                    "light": "hsla(300.86,66.67%,41.18%,0.68)",
                                    "dark": "hsla(300.86,66.67%,58.82%,0.68)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_primary-purple",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-t-3-purple",
                                "name": "primary-t-3",
                                "raw": "var(--at-primary-t-3)",
                                "rawValue": {
                                    "light": "hsla(300.86,66.67%,41.18%,0.53)",
                                    "dark": "hsla(300.86,66.67%,58.82%,0.53)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_primary-purple",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-t-4-purple",
                                "name": "primary-t-4",
                                "raw": "var(--at-primary-t-4)",
                                "rawValue": {
                                    "light": "hsla(300.86,66.67%,41.18%,0.37)",
                                    "dark": "hsla(300.86,66.67%,58.82%,0.37)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_primary-purple",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-t-5-purple",
                                "name": "primary-t-5",
                                "raw": "var(--at-primary-t-5)",
                                "rawValue": {
                                    "light": "hsla(300.86,66.67%,41.18%,0.21)",
                                    "dark": "hsla(300.86,66.67%,58.82%,0.21)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_primary-purple",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_primary-t-6-purple",
                                "name": "primary-t-6",
                                "raw": "var(--at-primary-t-6)",
                                "rawValue": {
                                    "light": "hsla(300.86,66.67%,41.18%,0.05)",
                                    "dark": "hsla(300.86,66.67%,58.82%,0.05)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_primary-purple",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-purple",
                                "name": "secondary",
                                "raw": "var(--at-secondary)",
                                "rawValue": {
                                    "light": "hsla(17, 68%, 37%, 1)",
                                    "dark": "hsla(17, 68%, 37%, 1)"
                                },
                                "complementaryChildren": [],
                                "shadeChildren": [
                                    "at_secondary-l-1-purple",
                                    "at_secondary-l-2-purple",
                                    "at_secondary-l-3-purple",
                                    "at_secondary-l-4-purple",
                                    "at_secondary-l-5-purple",
                                    "at_secondary-l-6-purple",
                                    "at_secondary-d-1-purple",
                                    "at_secondary-d-2-purple",
                                    "at_secondary-d-3-purple",
                                    "at_secondary-d-4-purple",
                                    "at_secondary-d-5-purple",
                                    "at_secondary-d-6-purple",
                                    "at_secondary-t-1-purple",
                                    "at_secondary-t-2-purple",
                                    "at_secondary-t-3-purple",
                                    "at_secondary-t-4-purple",
                                    "at_secondary-t-5-purple",
                                    "at_secondary-t-6-purple"
                                ],
                                "isExpanded": false,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-l-1-purple",
                                "name": "secondary-l-1",
                                "raw": "var(--at-secondary-l-1)",
                                "rawValue": {
                                    "light": "hsla(17.06,45.23%,47.25%,1)",
                                    "dark": "hsla(17.06,45.23%,52.75%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_secondary-purple",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-l-2-purple",
                                "name": "secondary-l-2",
                                "raw": "var(--at-secondary-l-2)",
                                "rawValue": {
                                    "light": "hsla(17.05,40.37%,57.25%,1)",
                                    "dark": "hsla(17.05,40.37%,42.75%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_secondary-purple",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-l-3-purple",
                                "name": "secondary-l-3",
                                "raw": "var(--at-secondary-l-3)",
                                "rawValue": {
                                    "light": "hsla(17.65,40.96%,67.45%,1)",
                                    "dark": "hsla(17.65,40.96%,32.55%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_secondary-purple",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-l-4-purple",
                                "name": "secondary-l-4",
                                "raw": "var(--at-secondary-l-4)",
                                "rawValue": {
                                    "light": "hsla(17.5,42.11%,77.65%,1)",
                                    "dark": "hsla(17.5,42.11%,22.35%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_secondary-purple",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-l-5-purple",
                                "name": "secondary-l-5",
                                "raw": "var(--at-secondary-l-5)",
                                "rawValue": {
                                    "light": "hsla(17.78,42.86%,87.65%,1)",
                                    "dark": "hsla(17.78,42.86%,12.35%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_secondary-purple",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-l-6-purple",
                                "name": "secondary-l-6",
                                "raw": "var(--at-secondary-l-6)",
                                "rawValue": {
                                    "light": "hsla(17.14,63.64%,97.84%,1)",
                                    "dark": "hsla(17.14,63.64%,2.16%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_secondary-purple",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-d-1-purple",
                                "name": "secondary-d-1",
                                "raw": "var(--at-secondary-d-1)",
                                "rawValue": {
                                    "light": "hsla(16.91,67.9%,31.76%,1)",
                                    "dark": "hsla(16.91,67.9%,68.24%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_secondary-purple",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-d-2-purple",
                                "name": "secondary-d-2",
                                "raw": "var(--at-secondary-d-2)",
                                "rawValue": {
                                    "light": "hsla(17.61,68.66%,26.27%,1)",
                                    "dark": "hsla(17.61,68.66%,73.73%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_secondary-purple",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-d-3-purple",
                                "name": "secondary-d-3",
                                "raw": "var(--at-secondary-d-3)",
                                "rawValue": {
                                    "light": "hsla(17.26,68.22%,20.98%,1)",
                                    "dark": "hsla(17.26,68.22%,79.02%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_secondary-purple",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-d-4-purple",
                                "name": "secondary-d-4",
                                "raw": "var(--at-secondary-d-4)",
                                "rawValue": {
                                    "light": "hsla(16.67,67.5%,15.69%,1)",
                                    "dark": "hsla(16.67,67.5%,84.31%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_secondary-purple",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-d-5-purple",
                                "name": "secondary-d-5",
                                "raw": "var(--at-secondary-d-5)",
                                "rawValue": {
                                    "light": "hsla(18.33,69.23%,10.2%,1)",
                                    "dark": "hsla(18.33,69.23%,89.8%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_secondary-purple",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-d-6-purple",
                                "name": "secondary-d-6",
                                "raw": "var(--at-secondary-d-6)",
                                "rawValue": {
                                    "light": "hsla(17.65,68%,4.9%,1)",
                                    "dark": "hsla(17.65,68%,95.1%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_secondary-purple",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-t-1-purple",
                                "name": "secondary-t-1",
                                "raw": "var(--at-secondary-t-1)",
                                "rawValue": {
                                    "light": "hsla(17.21,68.25%,37.06%,0.84)",
                                    "dark": "hsla(17.21,68.25%,62.94%,0.84)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_secondary-purple",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-t-2-purple",
                                "name": "secondary-t-2",
                                "raw": "var(--at-secondary-t-2)",
                                "rawValue": {
                                    "light": "hsla(17.21,68.25%,37.06%,0.68)",
                                    "dark": "hsla(17.21,68.25%,62.94%,0.68)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_secondary-purple",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-t-3-purple",
                                "name": "secondary-t-3",
                                "raw": "var(--at-secondary-t-3)",
                                "rawValue": {
                                    "light": "hsla(17.21,68.25%,37.06%,0.53)",
                                    "dark": "hsla(17.21,68.25%,62.94%,0.53)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_secondary-purple",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-t-4-purple",
                                "name": "secondary-t-4",
                                "raw": "var(--at-secondary-t-4)",
                                "rawValue": {
                                    "light": "hsla(17.21,68.25%,37.06%,0.37)",
                                    "dark": "hsla(17.21,68.25%,62.94%,0.37)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_secondary-purple",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-t-5-purple",
                                "name": "secondary-t-5",
                                "raw": "var(--at-secondary-t-5)",
                                "rawValue": {
                                    "light": "hsla(17.21,68.25%,37.06%,0.21)",
                                    "dark": "hsla(17.21,68.25%,62.94%,0.21)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_secondary-purple",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_secondary-t-6-purple",
                                "name": "secondary-t-6",
                                "raw": "var(--at-secondary-t-6)",
                                "rawValue": {
                                    "light": "hsla(17.21,68.25%,37.06%,0.05)",
                                    "dark": "hsla(17.21,68.25%,62.94%,0.05)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_secondary-purple",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-purple",
                                "name": "neutral",
                                "raw": "var(--at-neutral)",
                                "rawValue": {
                                    "light": "hsla(301, 12%, 50%, 1)",
                                    "dark": "hsla(301, 12%, 50%, 1)"
                                },
                                "complementaryChildren": [],
                                "shadeChildren": [
                                    "at_neutral-l-1-purple",
                                    "at_neutral-l-2-purple",
                                    "at_neutral-l-3-purple",
                                    "at_neutral-l-4-purple",
                                    "at_neutral-l-5-purple",
                                    "at_neutral-l-6-purple",
                                    "at_neutral-d-1-purple",
                                    "at_neutral-d-2-purple",
                                    "at_neutral-d-3-purple",
                                    "at_neutral-d-4-purple",
                                    "at_neutral-d-5-purple",
                                    "at_neutral-d-6-purple",
                                    "at_neutral-t-1-purple",
                                    "at_neutral-t-2-purple",
                                    "at_neutral-t-3-purple",
                                    "at_neutral-t-4-purple",
                                    "at_neutral-t-5-purple",
                                    "at_neutral-t-6-purple"
                                ],
                                "isExpanded": false,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-l-1-purple",
                                "name": "neutral-l-1",
                                "raw": "var(--at-neutral-l-1)",
                                "rawValue": {
                                    "light": "hsla(302.31,12.15%,58.04%,1)",
                                    "dark": "hsla(302.31,12.15%,41.96%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_neutral-purple",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-l-2-purple",
                                "name": "neutral-l-2",
                                "raw": "var(--at-neutral-l-2)",
                                "rawValue": {
                                    "light": "hsla(302.86,12.14%,66.08%,1)",
                                    "dark": "hsla(302.86,12.14%,33.92%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_neutral-purple",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-l-3-purple",
                                "name": "neutral-l-3",
                                "raw": "var(--at-neutral-l-3)",
                                "rawValue": {
                                    "light": "hsla(303.75,12.12%,74.12%,1)",
                                    "dark": "hsla(303.75,12.12%,25.88%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_neutral-purple",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-l-4-purple",
                                "name": "neutral-l-4",
                                "raw": "var(--at-neutral-l-4)",
                                "rawValue": {
                                    "light": "hsla(305,13.04%,81.96%,1)",
                                    "dark": "hsla(305,13.04%,18.04%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_neutral-purple",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-l-5-purple",
                                "name": "neutral-l-5",
                                "raw": "var(--at-neutral-l-5)",
                                "rawValue": {
                                    "light": "hsla(308.57,13.73%,90%,1)",
                                    "dark": "hsla(308.57,13.73%,10%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_neutral-purple",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-l-6-purple",
                                "name": "neutral-l-6",
                                "raw": "var(--at-neutral-l-6)",
                                "rawValue": {
                                    "light": "hsla(330,20%,98.04%,1)",
                                    "dark": "hsla(330,20%,1.96%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Light",
                                "shadeParent": "at_neutral-purple",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-d-1-purple",
                                "name": "neutral-d-1",
                                "raw": "var(--at-neutral-d-1)",
                                "rawValue": {
                                    "light": "hsla(302.22,12.44%,42.55%,1)",
                                    "dark": "hsla(302.22,12.44%,57.45%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_neutral-purple",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-d-2-purple",
                                "name": "neutral-d-2",
                                "raw": "var(--at-neutral-d-2)",
                                "rawValue": {
                                    "light": "hsla(302.73,12.36%,34.9%,1)",
                                    "dark": "hsla(302.73,12.36%,65.1%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_neutral-purple",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-d-3-purple",
                                "name": "neutral-d-3",
                                "raw": "var(--at-neutral-d-3)",
                                "rawValue": {
                                    "light": "hsla(303.53,12.06%,27.65%,1)",
                                    "dark": "hsla(303.53,12.06%,72.35%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_neutral-purple",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-d-4-purple",
                                "name": "neutral-d-4",
                                "raw": "var(--at-neutral-d-4)",
                                "rawValue": {
                                    "light": "hsla(300,11.76%,20%,1)",
                                    "dark": "hsla(300,11.76%,80%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_neutral-purple",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-d-5-purple",
                                "name": "neutral-d-5",
                                "raw": "var(--at-neutral-d-5)",
                                "rawValue": {
                                    "light": "hsla(307.5,12.5%,12.55%,1)",
                                    "dark": "hsla(307.5,12.5%,87.45%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_neutral-purple",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-d-6-purple",
                                "name": "neutral-d-6",
                                "raw": "var(--at-neutral-d-6)",
                                "rawValue": {
                                    "light": "hsla(300,12%,4.9%,1)",
                                    "dark": "hsla(300,12%,95.1%,1)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Dark",
                                "shadeParent": "at_neutral-purple",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-t-1-purple",
                                "name": "neutral-t-1",
                                "raw": "var(--at-neutral-t-1)",
                                "rawValue": {
                                    "light": "hsla(301.94,12.16%,50%,0.84)",
                                    "dark": "hsla(301.94,12.16%,50%,0.84)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_neutral-purple",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-t-2-purple",
                                "name": "neutral-t-2",
                                "raw": "var(--at-neutral-t-2)",
                                "rawValue": {
                                    "light": "hsla(301.94,12.16%,50%,0.68)",
                                    "dark": "hsla(301.94,12.16%,50%,0.68)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_neutral-purple",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-t-3-purple",
                                "name": "neutral-t-3",
                                "raw": "var(--at-neutral-t-3)",
                                "rawValue": {
                                    "light": "hsla(301.94,12.16%,50%,0.53)",
                                    "dark": "hsla(301.94,12.16%,50%,0.53)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_neutral-purple",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-t-4-purple",
                                "name": "neutral-t-4",
                                "raw": "var(--at-neutral-t-4)",
                                "rawValue": {
                                    "light": "hsla(301.94,12.16%,50%,0.37)",
                                    "dark": "hsla(301.94,12.16%,50%,0.37)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_neutral-purple",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-t-5-purple",
                                "name": "neutral-t-5",
                                "raw": "var(--at-neutral-t-5)",
                                "rawValue": {
                                    "light": "hsla(301.94,12.16%,50%,0.21)",
                                    "dark": "hsla(301.94,12.16%,50%,0.21)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_neutral-purple",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_neutral-t-6-purple",
                                "name": "neutral-t-6",
                                "raw": "var(--at-neutral-t-6)",
                                "rawValue": {
                                    "light": "hsla(301.94,12.16%,50%,0.05)",
                                    "dark": "hsla(301.94,12.16%,50%,0.05)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_neutral-purple",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black-purple",
                                "name": "black",
                                "raw": "var(--at-black)",
                                "rawValue": {
                                    "light": "hsla(0, 0%, 0%, 1)",
                                    "dark": "#ffffff"
                                },
                                "complementaryChildren": [],
                                "shadeChildren": [
                                    "at_black-t-1-purple",
                                    "at_black-t-2-purple",
                                    "at_black-t-3-purple",
                                    "at_black-t-4-purple",
                                    "at_black-t-5-purple",
                                    "at_black-t-6-purple"
                                ],
                                "isExpanded": false,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black-t-1-purple",
                                "name": "black-t-1",
                                "raw": "var(--at-black-t-1)",
                                "rawValue": {
                                    "light": "hsla(0,0%,0%,0.84)",
                                    "dark": "hsla(0,0%,100%,0.84)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_black-purple",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black-t-2-purple",
                                "name": "black-t-2",
                                "raw": "var(--at-black-t-2)",
                                "rawValue": {
                                    "light": "hsla(0,0%,0%,0.68)",
                                    "dark": "hsla(0,0%,100%,0.68)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_black-purple",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black-t-3-purple",
                                "name": "black-t-3",
                                "raw": "var(--at-black-t-3)",
                                "rawValue": {
                                    "light": "hsla(0,0%,0%,0.53)",
                                    "dark": "hsla(0,0%,100%,0.53)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_black-purple",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black-t-4-purple",
                                "name": "black-t-4",
                                "raw": "var(--at-black-t-4)",
                                "rawValue": {
                                    "light": "hsla(0,0%,0%,0.37)",
                                    "dark": "hsla(0,0%,100%,0.37)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_black-purple",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black-t-5-purple",
                                "name": "black-t-5",
                                "raw": "var(--at-black-t-5)",
                                "rawValue": {
                                    "light": "hsla(0,0%,0%,0.21)",
                                    "dark": "hsla(0,0%,100%,0.21)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_black-purple",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_black-t-6-purple",
                                "name": "black-t-6",
                                "raw": "var(--at-black-t-6)",
                                "rawValue": {
                                    "light": "hsla(0,0%,0%,0.05)",
                                    "dark": "hsla(0,0%,100%,0.05)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_black-purple",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white-purple",
                                "name": "white",
                                "raw": "var(--at-white)",
                                "rawValue": {
                                    "light": "#ffffff",
                                    "dark": "#000000"
                                },
                                "complementaryChildren": [],
                                "shadeChildren": [
                                    "at_white-t-1-purple",
                                    "at_white-t-2-purple",
                                    "at_white-t-3-purple",
                                    "at_white-t-4-purple",
                                    "at_white-t-5-purple",
                                    "at_white-t-6-purple"
                                ],
                                "isExpanded": false,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white-t-1-purple",
                                "name": "white-t-1",
                                "raw": "var(--at-white-t-1)",
                                "rawValue": {
                                    "light": "hsla(0,0%,100%,0.84)",
                                    "dark": "hsla(0,0%,0%,0.84)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_white-purple",
                                "shadeOrder": 0,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white-t-2-purple",
                                "name": "white-t-2",
                                "raw": "var(--at-white-t-2)",
                                "rawValue": {
                                    "light": "hsla(0,0%,100%,0.68)",
                                    "dark": "hsla(0,0%,0%,0.68)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_white-purple",
                                "shadeOrder": 1,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white-t-3-purple",
                                "name": "white-t-3",
                                "raw": "var(--at-white-t-3)",
                                "rawValue": {
                                    "light": "hsla(0,0%,100%,0.53)",
                                    "dark": "hsla(0,0%,0%,0.53)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_white-purple",
                                "shadeOrder": 2,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white-t-4-purple",
                                "name": "white-t-4",
                                "raw": "var(--at-white-t-4)",
                                "rawValue": {
                                    "light": "hsla(0,0%,100%,0.37)",
                                    "dark": "hsla(0,0%,0%,0.37)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_white-purple",
                                "shadeOrder": 3,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white-t-5-purple",
                                "name": "white-t-5",
                                "raw": "var(--at-white-t-5)",
                                "rawValue": {
                                    "light": "hsla(0,0%,100%,0.21)",
                                    "dark": "hsla(0,0%,0%,0.21)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_white-purple",
                                "shadeOrder": 4,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            },
                            {
                                "id": "at_white-t-6-purple",
                                "name": "white-t-6",
                                "raw": "var(--at-white-t-6)",
                                "rawValue": {
                                    "light": "hsla(0,0%,100%,0.05)",
                                    "dark": "hsla(0,0%,0%,0.05)"
                                },
                                "isShade": true,
                                "shadeMode": "auto",
                                "shadeType": "Transparent",
                                "shadeParent": "at_white-purple",
                                "shadeOrder": 5,
                                "at_framework": true,
                                "at_version": "1.0.0"
                            }
                        ]
                    }
                ]',
            "theme_settings_new" => '[
                {
                    "label": "General",
                    "key": "general",
                    "props": {
                        "siteBackground": {
                            "color": {
                                "raw": "var(--at-white)"
                            }
                        }
                    }
                },
                {
                    "label": "Colors",
                    "key": "colors",
                    "props": {
                        "colorPrimary": {
                            "raw": "var(--at-primary)"
                        },
                        "colorSecondary": {
                            "raw": "var(--at-secondary)"
                        },
                        "colorLight": {
                            "raw": "var(--at-primary-l-5)"
                        },
                        "colorDark": {
                            "raw": "var(--at-primary-d-5)"
                        },
                        "colorMuted": {
                            "raw": "var(--at-neutral-l-2)"
                        },
                        "colorInfo": {
                            "raw": "var(--at-info)"
                        },
                        "colorSuccess": {
                            "raw": "var(--at-success)"
                        },
                        "colorWarning": {
                            "raw": "var(--at-warning)"
                        },
                        "colorDanger": {
                            "raw": "var(--at-danger)"
                        }
                    }
                },
                {
                    "label": "Links",
                    "key": "links",
                    "props": {
                        "typography": {
                            "color": {
                                "raw": "var(--at-link-color)"
                            }
                        },
                        "typography:hover": {
                            "color": {
                                "raw": "var(--at-link-color--hover)"
                            }
                        },
                        "typography:active": {
                            "color": {
                                "raw": "var(--at-link-color--active)"
                            }
                        },
                        "cssSelectors": ":where(.brxe-accordion .accordion-content-wrapper) a, :where(.brxe-icon-box .content) a, :where(.brxe-list) a, :where(.brxe-post-content):not([data-source=bricks]) a, :where(.brxe-posts .dynamic p) a, :where(.brxe-shortcode) a, :where(.brxe-tabs .tab-content) a, :where(.brxe-team-members) .description a, :where(.brxe-testimonials) .testimonial-content-wrapper a, :where(.brxe-text) a, :where(a.brxe-text), :where(.brxe-text-basic) a, :where(a.brxe-text-basic), :where(.brxe-post-comments) .comment-content a, :where(.brxe-text-link)"
                    }
                },
                {
                    "label": "Typography",
                    "key": "typography",
                    "props": {
                        "typographyHtml": "calc(var(--base-font) / 16 * 100%)",
                        "typographyBody": {
                            "font-size": "var(--at-text--s)"
                        },
                        "typographyHeadingH1": {
                            "font-size": "var(--at-heading--xl)"
                        },
                        "typographyHeadingH2": {
                            "font-size": "var(--at-heading--l)"
                        },
                        "typographyHeadingH3": {
                            "font-size": "var(--at-heading--m)"
                        },
                        "typographyHeadingH4": {
                            "font-size": "var(--at-heading--s)"
                        },
                        "typographyHeadingH5": {
                            "font-size": "var(--at-heading--xs)"
                        },
                        "typographyHeadingH6": {
                            "font-size": "var(--at-heading--2xs)"
                        },
                        "focusOutline": "var(--at-focus-outline-width) solid var(--at-focus-outline-color)"
                    }
                },
                {
                    "label": "Element - Section",
                    "key": "section",
                    "props": {
                        "padding": {
                            "top": "var(--at-section-padding-block)",
                            "bottom": "var(--at-section-padding-block)",
                            "right": "var(--at-gutter)",
                            "left": "var(--at-gutter)"
                        },
                        "widthMax": "var(--at-section-max-width)",
                        "_rowGap": "var(--at-container-gap)"
                    }
                },
                {
                    "label": "Element - Container",
                    "key": "container",
                    "props": {
                        "width": "100%",
                        "widthMax": "var(--at-site-box-max-width)",
                        "_rowGap": "var(--at-content-gap)"
                    }
                },
                {
                    "label": "Element - Block",
                    "key": "block",
                    "props": {
                        "_rowGap": "var(--at-content-gap)",
                        "_columnGap": "var(--at-content-gap)"
                    }
                },
                {
                    "label": "Element - Button",
                    "key": "button",
                    "props": {
                        "background": {
                            "raw": "var(--at-btn-primary-background)"
                        },
                        "background:hover": {
                            "raw": "var(--at-btn-primary-background--hover)"
                        },
                        "border": {
                            "color": {
                                "raw": "var(--at-btn-primary-border-color)"
                            },
                            "width": {
                                "top": "var(--at-btn-border-width)",
                                "bottom": "var(--at-btn-border-width)",
                                "right": "var(--at-btn-border-width)",
                                "left": "var(--at-btn-border-width)"
                            },
                            "radius": {
                                "top": "var(--at-btn-border-radius)",
                                "right": "var(--at-btn-border-radius)",
                                "bottom": "var(--at-btn-border-radius)",
                                "left": "var(--at-btn-border-radius)"
                            },
                            "style": "solid"
                        },
                        "border:hover": {
                            "color": {
                                "raw": "var(--at-btn-primary-border-color--hover)"
                            },
                            "width": {
                                "top": "var(--at-btn-border-width--hover)",
                                "bottom": "var(--at-btn-border-width--hover)",
                                "right": "var(--at-btn-border-width--hover)",
                                "left": "var(--at-btn-border-width--hover)"
                            },
                            "radius": {
                                "top": "var(--at-btn-border-radius--hover)",
                                "right": "var(--at-btn-border-radius--hover)",
                                "bottom": "var(--at-btn-border-radius--hover)",
                                "left": "var(--at-btn-border-radius--hover)"
                            }
                        },
                        "boxShadow": {
                            "color": {
                                "raw": "var(--at-btn-shadow)"
                            }        
                        },
                        "boxShadow:hover": {
                            "color": {
                                "raw": "var(--at-btn-shadow--hover)"
                            }        
                        },
                        "transition": "var(--at-btn-transition)",
                        "typography": {
                            "color": {
                                "raw": "var(--at-btn-primary-color)"
                            },
                            "font-size": "var(--at-btn-medium-font-size)",
                            "font-style": "normal",
                            "font-weight": "600",
                            "letter-spacing": "0.2",
                            "line-height": "1",
                            "text-align": "center",
                            "text-decoration": "none",
                            "text-transform": "uppercase",
                            "text-wrap": "nowrap",
                            "white-space": "nowrap"
                        },
                        "typography:hover": {
                            "color": {
                                "raw": "var(--at-btn-primary-color--hover)"
                            }
                        },
                        "outlineTypography": {
                            "color": {
                                "raw": "var(--at-btn-primary-outline-color)"
                            }
                        },
                        "outlineBackground": {
                            "raw": "var(--at-btn-primary-outline-background)"
                        },
                        "outlineBorder": {
                            "color": {
                                "raw": "var(--at-btn-primary-outline-border-color)"
                            },
                            "width": {
                                "top": "var(--at-btn-outline-border-width)",
                                "bottom": "var(--at-btn-outline-border-width)",
                                "right": "var(--at-btn-outline-border-width)",
                                "left": "var(--at-btn-outline-border-width)"
                            },
                            "radius": {
                                "top": "var(--at-btn-outline-border-radius)",
                                "right": "var(--at-btn-outline-border-radius)",
                                "bottom": "var(--at-btn-outline-border-radius)",
                                "left": "var(--at-btn-outline-border-radius)"
                            }
                        },
                        "outlineBoxShadow": {
                            "color": {
                                "raw": "var(--at-btn-outline-shadow)"
                            }        
                        },
                        "outlineBoxShadow:hover": {
                            "color": {
                                "raw": "var(--at-btn-outline-shadow--hover)"
                            }        
                        },
                        "outlineTypography:hover": {
                            "color": {
                                "raw": "var(--at-btn-primary-outline-color--hover)"
                            }
                        },
                        "outlineBackground:hover": {
                            "raw": "var(--at-btn-primary-outline-background--hover)"
                        },
                        "outlineBorder:hover": {
                            "color": {
                                "raw": "var(--at-btn-primary-outline-border-color--hover)"
                            },
                            "width": {
                                "top": "var(--at-btn-outline-border-width--hover)",
                                "bottom": "var(--at-btn-outline-border-width--hover)",
                                "right": "var(--at-btn-outline-border-width--hover)",
                                "left": "var(--at-btn-outline-border-width--hover)"
                            }
                        },
                        "primaryTypography": {
                            "color": {
                                "raw": "var(--at-btn-primary-color)"
                            }
                        },
                        "primaryBackground": {
                            "raw": "var(--at-btn-primary-background)"
                        },
                        "primaryBorder": {
                            "color": {
                                "raw": "var(--at-btn-primary-border-color)"
                            }
                        },
                        "primaryTypography:hover": {
                            "color": {
                                "raw": "var(--at-btn-primary-color--hover)"
                            }
                        },
                        "primaryBackground:hover": {
                            "raw": "var(--at-btn-primary-background--hover)"
                        },
                        "primaryBorder:hover": {
                            "color": {
                                "raw": "var(--at-btn-primary-border-color--hover)"
                            }
                        },
                        "primaryOutlineTypography": {
                            "color": {
                                "raw": "var(--at-btn-primary-outline-color)"
                            }
                        },
                        "primaryOutlineBackground": {
                            "raw": "var(--at-btn-primary-outline-background)"
                        },
                        "primaryOutlineBorder": {
                            "color": {
                                "raw": "var(--at-btn-primary-outline-border-color)"
                            }
                        },
                        "primaryOutlineTypography:hover": {
                            "color": {
                                "raw": "var(--at-btn-primary-outline-color--hover)"
                            }
                        },
                        "primaryOutlineBackground:hover": {
                            "raw": "var(--at-btn-primary-outline-background--hover)"
                        },
                        "primaryOutlineBorder:hover": {
                            "color": {
                                "raw": "var(--at-btn-primary-outline-border-color--hover)"
                            }
                        },
                        "secondaryTypography": {
                            "color": {
                                "raw": "var(--at-btn-secondary-color)"
                            }
                        },
                        "secondaryBackground": {
                            "raw": "var(--at-btn-secondary-background)"
                        },
                        "secondaryBorder": {
                            "color": {
                                "raw": "var(--at-btn-secondary-border-color)"
                            }
                        },
                        "secondaryTypography:hover": {
                            "color": {
                                "raw": "var(--at-btn-secondary-color--hover)"
                            }
                        },
                        "secondaryBackground:hover": {
                            "raw": "var(--at-btn-secondary-background--hover)"
                        },
                        "secondaryBorder:hover": {
                            "color": {
                                "raw": "var(--at-btn-secondary-border-color--hover)"
                            }
                        },
                        "secondaryOutlineTypography": {
                            "color": {
                                "raw": "var(--at-btn-secondary-outline-color)"
                            }
                        },
                        "secondaryOutlineBackground": {
                            "raw": "var(--at-btn-secondary-outline-background)"
                        },
                        "secondaryOutlineBorder": {
                            "color": {
                                "raw": "var(--at-btn-secondary-outline-border-color)"
                            }
                        },
                        "secondaryOutlineTypography:hover": {
                            "color": {
                                "raw": "var(--at-btn-secondary-outline-color--hover)"
                            }
                        },
                        "secondaryOutlineBackground:hover": {
                            "raw": "var(--at-btn-secondary-outline-background--hover)"
                        },
                        "secondaryOutlineBorder:hover": {
                            "color": {
                                "raw": "var(--at-btn-secondary-outline-border-color--hover)"
                            }
                        },
                        "lightTypography": {
                            "color": {
                                "raw": "var(--at-btn-light-color)"
                            }
                        },
                        "lightBackground": {
                            "raw": "var(--at-btn-light-background)"
                        },
                        "lightBorder": {
                            "color": {
                                "raw": "var(--at-btn-light-border-color)"
                            }
                        },
                        "lightTypography:hover": {
                            "color": {
                                "raw": "var(--at-btn-light-color--hover)"
                            }
                        },
                        "lightBackground:hover": {
                            "raw": "var(--at-btn-light-background--hover)"
                        },
                        "lightBorder:hover": {
                            "color": {
                                "raw": "var(--at-btn-light-border-color--hover)"
                            }
                        },
                        "lightOutlineTypography": {
                            "color": {
                                "raw": "var(--at-btn-light-outline-color)"
                            }
                        },
                        "lightOutlineBackground": {
                            "raw": "var(--at-btn-light-outline-background)"
                        },
                        "lightOutlineBorder": {
                            "color": {
                                "raw": "var(--at-btn-light-outline-border-color)"
                            }
                        },
                        "lightOutlineTypography:hover": {
                            "color": {
                                "raw": "var(--at-btn-light-outline-color--hover)"
                            }
                        },
                        "lightOutlineBackground:hover": {
                            "raw": "var(--at-btn-light-outline-background--hover)"
                        },
                        "lightOutlineBorder:hover": {
                            "color": {
                                "raw": "var(--at-btn-light-outline-border-color--hover)"
                            }
                        },
                        "darkTypography": {
                            "color": {
                                "raw": "var(--at-btn-dark-color)"
                            }
                        },
                        "darkBackground": {
                            "raw": "var(--at-btn-dark-background)"
                        },
                        "darkBorder": {
                            "color": {
                                "raw": "var(--at-btn-dark-border-color)"
                            }
                        },
                        "darkTypography:hover": {
                            "color": {
                                "raw": "var(--at-btn-dark-color--hover)"
                            }
                        },
                        "darkBackground:hover": {
                            "raw": "var(--at-btn-dark-background--hover)"
                        },
                        "darkBorder:hover": {
                            "color": {
                                "raw": "var(--at-btn-dark-border-color--hover)"
                            }
                        },
                        "darkOutlineTypography": {
                            "color": {
                                "raw": "var(--at-btn-dark-outline-color)"
                            }
                        },
                        "darkOutlineBackground": {
                            "raw": "var(--at-btn-dark-outline-background)"
                        },
                        "darkOutlineBorder": {
                            "color": {
                                "raw": "var(--at-btn-dark-outline-border-color)"
                            }
                        },
                        "darkOutlineTypography:hover": {
                            "color": {
                                "raw": "var(--at-btn-dark-outline-color--hover)"
                            }
                        },
                        "darkOutlineBackground:hover": {
                            "raw": "var(--at-btn-dark-outline-background--hover)"
                        },
                        "darkOutlineBorder:hover": {
                            "color": {
                                "raw": "var(--at-btn-dark-outline-border-color--hover)"
                            }
                        },
                        "sizeDefaultPadding": {
                            "bottom": "var(--at-btn-medium-padding-block)",
                            "top": "var(--at-btn-medium-padding-block)",
                            "right": "var(--at-btn-medium-padding-inline)",
                            "left": "var(--at-btn-medium-padding-inline)"
                        },
                        "sizeSmPadding": {
                            "top": "var(--at-btn-small-padding-block)",
                            "right": "var(--at-btn-small-padding-inline)",
                            "left": "var(--at-btn-small-padding-inline)",
                            "bottom": "var(--at-btn-small-padding-block)"
                        },
                        "sizeSmTypography": {
                            "font-size": "var(--at-btn-small-font-size)"
                        },
                        "sizeMdPadding": {
                            "bottom": "var(--at-btn-medium-padding-block)",
                            "top": "var(--at-btn-medium-padding-block)",
                            "right": "var(--at-btn-medium-padding-inline)",
                            "left": "var(--at-btn-medium-padding-inline)"
                        },
                        "sizeMdTypography": {
                            "font-size": "var(--at-btn-medium-font-size)"
                        },
                        "sizeLgPadding": {
                            "bottom": "var(--at-btn-large-padding-block)",
                            "top": "var(--at-btn-large-padding-block)",
                            "right": "var(--at-btn-large-padding-inline)",
                            "left": "var(--at-btn-large-padding-inline)"
                        },
                        "sizeLgTypography": {
                            "font-size": "var(--at-btn-large-font-size)"
                        },
                        "sizeXlPadding": {
                            "bottom": "var(--at-btn-x-large-padding-block)",
                            "top": "var(--at-btn-x-large-padding-block)",
                            "right": "var(--at-btn-x-large-padding-inline)",
                            "left": "var(--at-btn-x-large-padding-inline)"
                        },
                        "sizeXlTypography": {
                            "font-size": "var(--at-btn-x-large-font-size)"
                        }
                    }
                },
                {
                    "label": "Element - Form",
                    "key": "form",
                    "props": {
                        "labelTypography": {
                            "font-size": "var(--at-form-label-font-size)",
                            "color": {
                                "raw": "var(--at-form-label-color)"
                            }
                        },
                        "placeholderTypography": {
                            "font-size": "var(--at-form-placeholder-font-size)",
                            "color": {
                                "raw": "var(--at-form-placeholder-color)"
                            }
                        },
                        "fieldTypography": {
                            "font-size": "var(--at-form-input-font-size)",
                            "color": {
                                "raw": "var(--at-form-input-color)"
                            }
                        },
                        "fieldBackgroundColor": {
                            "raw": "var(--at-form-input-background-color)"
                        },
                        "fieldBorder": {
                            "width": {
                                "top": "var(--at-form-input-border-width)",
                                "right": "var(--at-form-input-border-width)",
                                "bottom": "var(--at-form-input-border-width)",
                                "left": "var(--at-form-input-border-width)"
                            },
                            "color": {
                                "raw": "var(--at-form-input-border-color)"
                            },
                            "radius": {
                                "top": "var(--at-form-input-border-radius)",
                                "right": "var(--at-form-input-border-radius)",
                                "bottom": "var(--at-form-input-border-radius)",
                                "left": "var(--at-form-input-border-radius)"
                            }
                        },
                        "fieldMargin": {
                            "top": "var(--at-form-group-padding-block) ",
                            "bottom": "var(--at-form-group-padding-block) ",
                            "right": "var(--at-form-group-padding-inline)",
                            "left": "var(--at-form-group-padding-inline)"
                        },
                        "fieldPadding": {
                            "top": "var(--at-form-input-padding-block)",
                            "bottom": "var(--at-form-input-padding-block)",
                            "right": "var(--at-form-input-padding-inline)",
                            "left": "var(--at-form-input-padding-inline)"
                        },
                        "fieldTypography:focus": {
                            "color": {
                                "raw": "var(--at-form-input-color--focus)"
                            }
                        },
                        "fieldBackgroundColor:focus": {
                            "raw": "var(--at-form-input-background-color--focus)"
                        },
                        "fieldBorder:focus": {
                            "color": {
                                "raw": "var(--at-focus-outline-color)"
                            },
                            "radius": {
                                "top": "var(--at-form-input-border-radius--focus)",
                                "right": "var(--at-form-input-border-radius--focus)",
                                "bottom": "var(--at-form-input-border-radius--focus)",
                                "left": "var(--at-form-input-border-radius--focus)"
                            }
                        }
                    }
                },
                {
                    "label": "Element - Heading",
                    "key": "heading",
                    "props": {
                        "tag": "h2"
                    }
                }  
            ]',
            "theme_settings" => '
                {
                    "at_framework": {
                        "label": "AT Framework",
                        "at_framework": true,
                        "at_version": "1.1.0",
                        "settings": {
                            "_custom": true,
                            "conditions": {
                                "conditions": [
                                    {
                                        "id": "nqprte",
                                        "main": "any"
                                    }
                                ]
                            },
                            "typography": {
                                "typographyHtml": "calc(var(--base-font) / 16 * 100%)",
                                "typographyBody": {
                                    "font-size": "var(--at-text--s)"
                                },
                                "typographyHeadingH1": {
                                    "font-size": "var(--at-heading--xl)"
                                },
                                "typographyHeadingH2": {
                                    "font-size": "var(--at-heading--l)"
                                },
                                "typographyHeadingH3": {
                                    "font-size": "var(--at-heading--m)"
                                },
                                "typographyHeadingH4": {
                                    "font-size": "var(--at-heading--s)"
                                },
                                "typographyHeadingH5": {
                                    "font-size": "var(--at-heading--xs)"
                                },
                                "typographyHeadingH6": {
                                    "font-size": "var(--at-heading--2xs)"
                                },
                                "focusOutline": "var(--at-focus-outline-width) solid var(--at-focus-outline-color)"
                            },
                            "section": {
                                "padding": {
                                    "top": "var(--at-section-padding-block)",
                                    "bottom": "var(--at-section-padding-block)",
                                    "right": "var(--at-gutter)",
                                    "left": "var(--at-gutter)"
                                },
                                "widthMax": "var(--at-section-max-width)",
                                "_rowGap": "var(--at-container-gap)"
                            },
                            "container": {
                                "width": "100%",
                                "widthMax": "var(--at-site-box-max-width)",
                                "_rowGap": "var(--at-content-gap)"
                            },
                            "block": {
                                "_rowGap": "var(--at-content-gap)",
                                "_columnGap": "var(--at-content-gap)"
                            },
                            "links": {
                                "typography": {
                                    "color": {
                                        "raw": "var(--at-link-color)"
                                    }
                                },
                                "typography:hover": {
                                    "color": {
                                        "raw": "var(--at-link-color--hover)"
                                    }
                                },
                                "typography:active": {
                                    "color": {
                                        "raw": "var(--at-link-color--active)"
                                    }
                                },
                                "cssSelectors": ":where(.brxe-accordion .accordion-content-wrapper) a, :where(.brxe-icon-box .content) a, :where(.brxe-list) a, :where(.brxe-post-content):not([data-source=bricks]) a, :where(.brxe-posts .dynamic p) a, :where(.brxe-shortcode) a, :where(.brxe-tabs .tab-content) a, :where(.brxe-team-members) .description a, :where(.brxe-testimonials) .testimonial-content-wrapper a, :where(.brxe-text) a, :where(a.brxe-text), :where(.brxe-text-basic) a, :where(a.brxe-text-basic), :where(.brxe-post-comments) .comment-content a, :where(.brxe-text-link)"
                            },
                            "general": {
                                "siteBackground": {
                                    "color": {
                                        "raw": "var(--at-white)"
                                    }
                                }
                            },
                            "heading": {
                                "tag": "h2"
                            },
                            "button": {
                                "background": {
                                    "raw": "var(--at-btn-primary-background)"
                                },
                                "background:hover": {
                                    "raw": "var(--at-btn-primary-background--hover)"
                                },
                                "border": {
                                    "color": {
                                        "raw": "var(--at-btn-primary-border-color)"
                                    },
                                    "width": {
                                        "top": "var(--at-btn-border-width)",
                                        "bottom": "var(--at-btn-border-width)",
                                        "right": "var(--at-btn-border-width)",
                                        "left": "var(--at-btn-border-width)"
                                    },
                                    "radius": {
                                        "top": "var(--at-btn-border-radius)",
                                        "right": "var(--at-btn-border-radius)",
                                        "bottom": "var(--at-btn-border-radius)",
                                        "left": "var(--at-btn-border-radius)"
                                    },
                                    "style": "solid"
                                },
                                "border:hover": {
                                    "color": {
                                        "raw": "var(--at-btn-primary-border-color--hover)"
                                    },
                                    "width": {
                                        "top": "var(--at-btn-border-width--hover)",
                                        "bottom": "var(--at-btn-border-width--hover)",
                                        "right": "var(--at-btn-border-width--hover)",
                                        "left": "var(--at-btn-border-width--hover)"
                                    },
                                    "radius": {
                                        "top": "var(--at-btn-border-radius--hover)",
                                        "right": "var(--at-btn-border-radius--hover)",
                                        "bottom": "var(--at-btn-border-radius--hover)",
                                        "left": "var(--at-btn-border-radius--hover)"
                                    }
                                },
                                "boxShadow": {
                                    "color": {
                                        "raw": "var(--at-btn-shadow)"
                                    }        
                                },
                                "boxShadow:hover": {
                                    "color": {
                                        "raw": "var(--at-btn-shadow--hover)"
                                    }        
                                },
                                "transition": "var(--at-btn-transition)",
                                "typography": {
                                    "color": {
                                        "raw": "var(--at-btn-primary-color)"
                                    },
                                    "font-size": "var(--at-btn-medium-font-size)",
                                    "font-style": "normal",
                                    "font-weight": "600",
                                    "letter-spacing": "0.2",
                                    "line-height": "1",
                                    "text-align": "center",
                                    "text-decoration": "none",
                                    "text-transform": "uppercase",
                                    "text-wrap": "nowrap",
                                    "white-space": "nowrap"
                                },
                                "typography:hover": {
                                    "color": {
                                        "raw": "var(--at-btn-primary-color--hover)"
                                    }
                                },
                                "outlineTypography": {
                                    "color": {
                                        "raw": "var(--at-btn-primary-outline-color)"
                                    }
                                },
                                "outlineBackground": {
                                    "raw": "var(--at-btn-primary-outline-background)"
                                },
                                "outlineBorder": {
                                    "color": {
                                        "raw": "var(--at-btn-primary-outline-border-color)"
                                    },
                                    "width": {
                                        "top": "var(--at-btn-outline-border-width)",
                                        "bottom": "var(--at-btn-outline-border-width)",
                                        "right": "var(--at-btn-outline-border-width)",
                                        "left": "var(--at-btn-outline-border-width)"
                                    },
                                    "radius": {
                                        "top": "var(--at-btn-outline-border-radius)",
                                        "right": "var(--at-btn-outline-border-radius)",
                                        "bottom": "var(--at-btn-outline-border-radius)",
                                        "left": "var(--at-btn-outline-border-radius)"
                                    }
                                },
                                "outlineBoxShadow": {
                                    "color": {
                                        "raw": "var(--at-btn-outline-shadow)"
                                    }        
                                },
                                "outlineBoxShadow:hover": {
                                    "color": {
                                        "raw": "var(--at-btn-outline-shadow--hover)"
                                    }        
                                },
                                "outlineTypography:hover": {
                                    "color": {
                                        "raw": "var(--at-btn-primary-outline-color--hover)"
                                    }
                                },
                                "outlineBackground:hover": {
                                    "raw": "var(--at-btn-primary-outline-background--hover)"
                                },
                                "outlineBorder:hover": {
                                    "color": {
                                        "raw": "var(--at-btn-primary-outline-border-color--hover)"
                                    },
                                    "width": {
                                        "top": "var(--at-btn-outline-border-width--hover)",
                                        "bottom": "var(--at-btn-outline-border-width--hover)",
                                        "right": "var(--at-btn-outline-border-width--hover)",
                                        "left": "var(--at-btn-outline-border-width--hover)"
                                    }
                                },
                                "primaryTypography": {
                                    "color": {
                                        "raw": "var(--at-btn-primary-color)"
                                    }
                                },
                                "primaryBackground": {
                                    "raw": "var(--at-btn-primary-background)"
                                },
                                "primaryBorder": {
                                    "color": {
                                        "raw": "var(--at-btn-primary-border-color)"
                                    }
                                },
                                "primaryTypography:hover": {
                                    "color": {
                                        "raw": "var(--at-btn-primary-color--hover)"
                                    }
                                },
                                "primaryBackground:hover": {
                                    "raw": "var(--at-btn-primary-background--hover)"
                                },
                                "primaryBorder:hover": {
                                    "color": {
                                        "raw": "var(--at-btn-primary-border-color--hover)"
                                    }
                                },
                                "primaryOutlineTypography": {
                                    "color": {
                                        "raw": "var(--at-btn-primary-outline-color)"
                                    }
                                },
                                "primaryOutlineBackground": {
                                    "raw": "var(--at-btn-primary-outline-background)"
                                },
                                "primaryOutlineBorder": {
                                    "color": {
                                        "raw": "var(--at-btn-primary-outline-border-color)"
                                    }
                                },
                                "primaryOutlineTypography:hover": {
                                    "color": {
                                        "raw": "var(--at-btn-primary-outline-color--hover)"
                                    }
                                },
                                "primaryOutlineBackground:hover": {
                                    "raw": "var(--at-btn-primary-outline-background--hover)"
                                },
                                "primaryOutlineBorder:hover": {
                                    "color": {
                                        "raw": "var(--at-btn-primary-outline-border-color--hover)"
                                    }
                                },
                                "secondaryTypography": {
                                    "color": {
                                        "raw": "var(--at-btn-secondary-color)"
                                    }
                                },
                                "secondaryBackground": {
                                    "raw": "var(--at-btn-secondary-background)"
                                },
                                "secondaryBorder": {
                                    "color": {
                                        "raw": "var(--at-btn-secondary-border-color)"
                                    }
                                },
                                "secondaryTypography:hover": {
                                    "color": {
                                        "raw": "var(--at-btn-secondary-color--hover)"
                                    }
                                },
                                "secondaryBackground:hover": {
                                    "raw": "var(--at-btn-secondary-background--hover)"
                                },
                                "secondaryBorder:hover": {
                                    "color": {
                                        "raw": "var(--at-btn-secondary-border-color--hover)"
                                    }
                                },
                                "secondaryOutlineTypography": {
                                    "color": {
                                        "raw": "var(--at-btn-secondary-outline-color)"
                                    }
                                },
                                "secondaryOutlineBackground": {
                                    "raw": "var(--at-btn-secondary-outline-background)"
                                },
                                "secondaryOutlineBorder": {
                                    "color": {
                                        "raw": "var(--at-btn-secondary-outline-border-color)"
                                    }
                                },
                                "secondaryOutlineTypography:hover": {
                                    "color": {
                                        "raw": "var(--at-btn-secondary-outline-color--hover)"
                                    }
                                },
                                "secondaryOutlineBackground:hover": {
                                    "raw": "var(--at-btn-secondary-outline-background--hover)"
                                },
                                "secondaryOutlineBorder:hover": {
                                    "color": {
                                        "raw": "var(--at-btn-secondary-outline-border-color--hover)"
                                    }
                                },
                                "lightTypography": {
                                    "color": {
                                        "raw": "var(--at-btn-light-color)"
                                    }
                                },
                                "lightBackground": {
                                    "raw": "var(--at-btn-light-background)"
                                },
                                "lightBorder": {
                                    "color": {
                                        "raw": "var(--at-btn-light-border-color)"
                                    }
                                },
                                "lightTypography:hover": {
                                    "color": {
                                        "raw": "var(--at-btn-light-color--hover)"
                                    }
                                },
                                "lightBackground:hover": {
                                    "raw": "var(--at-btn-light-background--hover)"
                                },
                                "lightBorder:hover": {
                                    "color": {
                                        "raw": "var(--at-btn-light-border-color--hover)"
                                    }
                                },
                                "lightOutlineTypography": {
                                    "color": {
                                        "raw": "var(--at-btn-light-outline-color)"
                                    }
                                },
                                "lightOutlineBackground": {
                                    "raw": "var(--at-btn-light-outline-background)"
                                },
                                "lightOutlineBorder": {
                                    "color": {
                                        "raw": "var(--at-btn-light-outline-border-color)"
                                    }
                                },
                                "lightOutlineTypography:hover": {
                                    "color": {
                                        "raw": "var(--at-btn-light-outline-color--hover)"
                                    }
                                },
                                "lightOutlineBackground:hover": {
                                    "raw": "var(--at-btn-light-outline-background--hover)"
                                },
                                "lightOutlineBorder:hover": {
                                    "color": {
                                        "raw": "var(--at-btn-light-outline-border-color--hover)"
                                    }
                                },
                                "darkTypography": {
                                    "color": {
                                        "raw": "var(--at-btn-dark-color)"
                                    }
                                },
                                "darkBackground": {
                                    "raw": "var(--at-btn-dark-background)"
                                },
                                "darkBorder": {
                                    "color": {
                                        "raw": "var(--at-btn-dark-border-color)"
                                    }
                                },
                                "darkTypography:hover": {
                                    "color": {
                                        "raw": "var(--at-btn-dark-color--hover)"
                                    }
                                },
                                "darkBackground:hover": {
                                    "raw": "var(--at-btn-dark-background--hover)"
                                },
                                "darkBorder:hover": {
                                    "color": {
                                        "raw": "var(--at-btn-dark-border-color--hover)"
                                    }
                                },
                                "darkOutlineTypography": {
                                    "color": {
                                        "raw": "var(--at-btn-dark-outline-color)"
                                    }
                                },
                                "darkOutlineBackground": {
                                    "raw": "var(--at-btn-dark-outline-background)"
                                },
                                "darkOutlineBorder": {
                                    "color": {
                                        "raw": "var(--at-btn-dark-outline-border-color)"
                                    }
                                },
                                "darkOutlineTypography:hover": {
                                    "color": {
                                        "raw": "var(--at-btn-dark-outline-color--hover)"
                                    }
                                },
                                "darkOutlineBackground:hover": {
                                    "raw": "var(--at-btn-dark-outline-background--hover)"
                                },
                                "darkOutlineBorder:hover": {
                                    "color": {
                                        "raw": "var(--at-btn-dark-outline-border-color--hover)"
                                    }
                                },
                                "sizeDefaultPadding": {
                                    "bottom": "var(--at-btn-medium-padding-block)",
                                    "top": "var(--at-btn-medium-padding-block)",
                                    "right": "var(--at-btn-medium-padding-inline)",
                                    "left": "var(--at-btn-medium-padding-inline)"
                                },
                                "sizeSmPadding": {
                                    "top": "var(--at-btn-small-padding-block)",
                                    "right": "var(--at-btn-small-padding-inline)",
                                    "left": "var(--at-btn-small-padding-inline)",
                                    "bottom": "var(--at-btn-small-padding-block)"
                                },
                                "sizeSmTypography": {
                                    "font-size": "var(--at-btn-small-font-size)"
                                },
                                "sizeMdPadding": {
                                    "bottom": "var(--at-btn-medium-padding-block)",
                                    "top": "var(--at-btn-medium-padding-block)",
                                    "right": "var(--at-btn-medium-padding-inline)",
                                    "left": "var(--at-btn-medium-padding-inline)"
                                },
                                "sizeMdTypography": {
                                    "font-size": "var(--at-btn-medium-font-size)"
                                },
                                "sizeLgPadding": {
                                    "bottom": "var(--at-btn-large-padding-block)",
                                    "top": "var(--at-btn-large-padding-block)",
                                    "right": "var(--at-btn-large-padding-inline)",
                                    "left": "var(--at-btn-large-padding-inline)"
                                },
                                "sizeLgTypography": {
                                    "font-size": "var(--at-btn-large-font-size)"
                                },
                                "sizeXlPadding": {
                                    "bottom": "var(--at-btn-x-large-padding-block)",
                                    "top": "var(--at-btn-x-large-padding-block)",
                                    "right": "var(--at-btn-x-large-padding-inline)",
                                    "left": "var(--at-btn-x-large-padding-inline)"
                                },
                                "sizeXlTypography": {
                                    "font-size": "var(--at-btn-x-large-font-size)"
                                }
                            },
                            "form": {
                                "labelTypography": {
                                    "font-size": "var(--at-form-label-font-size)",
                                    "color": {
                                        "raw": "var(--at-form-label-color)"
                                    }
                                },
                                "placeholderTypography": {
                                    "font-size": "var(--at-form-placeholder-font-size)",
                                    "color": {
                                        "raw": "var(--at-form-placeholder-color)"
                                    }
                                },
                                "fieldTypography": {
                                    "font-size": "var(--at-form-input-font-size)",
                                    "color": {
                                        "raw": "var(--at-form-input-color)"
                                    }
                                },
                                "fieldBackgroundColor": {
                                    "raw": "var(--at-form-input-background-color)"
                                },
                                "fieldBorder": {
                                    "width": {
                                        "top": "var(--at-form-input-border-width)",
                                        "right": "var(--at-form-input-border-width)",
                                        "bottom": "var(--at-form-input-border-width)",
                                        "left": "var(--at-form-input-border-width)"
                                    },
                                    "color": {
                                        "raw": "var(--at-form-input-border-color)"
                                    },
                                    "radius": {
                                        "top": "var(--at-form-input-border-radius)",
                                        "right": "var(--at-form-input-border-radius)",
                                        "bottom": "var(--at-form-input-border-radius)",
                                        "left": "var(--at-form-input-border-radius)"
                                    }
                                },
                                "fieldMargin": {
                                    "top": "var(--at-form-group-padding-block) ",
                                    "bottom": "var(--at-form-group-padding-block) ",
                                    "right": "var(--at-form-group-padding-inline)",
                                    "left": "var(--at-form-group-padding-inline)"
                                },
                                "fieldPadding": {
                                    "top": "var(--at-form-input-padding-block)",
                                    "bottom": "var(--at-form-input-padding-block)",
                                    "right": "var(--at-form-input-padding-inline)",
                                    "left": "var(--at-form-input-padding-inline)"
                                },
                                "fieldTypography:focus": {
                                    "color": {
                                        "raw": "var(--at-form-input-color--focus)"
                                    }
                                },
                                "fieldBackgroundColor:focus": {
                                    "raw": "var(--at-form-input-background-color--focus)"
                                },
                                "fieldBorder:focus": {
                                    "color": {
                                        "raw": "var(--at-focus-outline-color)"
                                    },
                                    "radius": {
                                        "top": "var(--at-form-input-border-radius--focus)",
                                        "right": "var(--at-form-input-border-radius--focus)",
                                        "bottom": "var(--at-form-input-border-radius--focus)",
                                        "left": "var(--at-form-input-border-radius--focus)"
                                    }
                                }
                            },
                            "colors": {
                                "colorPrimary": {
                                    "raw": "var(--at-primary)"
                                },
                                "colorSecondary": {
                                    "raw": "var(--at-secondary)"
                                },
                                "colorLight": {
                                    "raw": "var(--at-primary-l-5)"
                                },
                                "colorDark": {
                                    "raw": "var(--at-primary-d-5)"
                                },
                                "colorMuted": {
                                    "raw": "var(--at-neutral-l-2)"
                                },
                                "colorInfo": {
                                    "raw": "var(--at-info)"
                                },
                                "colorSuccess": {
                                    "raw": "var(--at-success)"
                                },
                                "colorWarning": {
                                    "raw": "var(--at-warning)"
                                },
                                "colorDanger": {
                                    "raw": "var(--at-danger)"
                                }
                            }
                        }
                    }
                }',
            "advanced_css" => '
                [
                    {
                        "id":"at_framework",
                        "file":false,
                        "label":"globals--v1.0.css",
                        "type":"css",
                        "typeLabel":"css",
                        "category":"at framework",
                        "scope":"global",
                        "message":"",
                        "readOnly":"",
                        "contentCss":"/****************\nWherever possible AT Framework variables and Classes are defined in the Bricks Class System.\nThis stylesheet is for any rules that we want to be always output, even if they are not added to a Bricks Elements.\n****************/\n\n/****************\nBRICKS OVERRIDES \n****************/\n\n/* fix P margins in Bricks Blocks */\n.brxe-block>p {\n  margin: 0;\n}\n\n/* ensure footer goes to bottom, even if there isn not enough content*/\nbody {\n  min-height: 100vh;\n  min-height: 100svh;\n  min-height: 100dvh;\n}\n\n/****************\nALTERNATE SECTION BACKGROUND\n\nAdd the classes to the page classes.\nApply to #brx-content > section elements\n****************/\n\n:where(.at-section--alt-odd #brx-content > section:nth-child(odd)) {\n  background: var(--at-section--alt-background);\n}\n\n:where(.at-section--alt-even #brx-content > section:nth-child(even)) {\n  background: var(--at-section--alt-background);\n}\n\n/****************\nSTICKY HEADERS\n\nSet a variable for the Bricks Header height.\nThis may be useful for when the headers are sticky.\n\nWe recommend keeping the varuable dymamic with JavaScript\n@see https://gist.github.com/wpeasy/66995b6b153edd8142dd4646df41cd19\nAdd this to your code manage and comment out below\n\nManually set the header height at different breakpoints.\nComment all of this out if you impliment the JavaScript linked above\n****************/\n\n@media(max-width: 477px) {\n  body {\n    --at-header-height: 150px;\n    /* Mobile - Measure and set manually */\n  }\n}\n\n@media(max-width: 766px) {\n  body {\n    --at-header-height: 150px;\n    /* Mobile Landscape - Measure and set manually */\n  }\n}\n\n@media(max-width: 990px) {\n  body {\n    --at-header-height: 150px;\n    /* Tablet Portrait - Measure and set manually */\n  }\n}\n\n@media(min-width: 991px) {\n  body {\n    --at-header-height: 150px;\n    /* Desktop and Above - Measure and set manually */\n  }\n}\n\n/***************\nA11y \n****************/\n\n/* focus offset styles */\nbody.bricks-is-frontend :focus-visible {\n  outline-offset: var(--at-focus-outline-offset);\n  z-index: 1;\n}\n\n/* reduced motion styles */\n@media (prefers-reduced-motion: reduce) {\n  * {\n    animation: none !important;\n    transition: none !important;\n    scroll-behavior: auto !important;\n  }\n\n  /* allow for transitions that fade in staring with low opacity */\n  [class*=fade-],\n  [class*=-fade] {\n    opacity: 1 !important;\n  }\n}",
                        "contentSass":"",
                        "order":5,
                        "status":"1",
                        "priority":"10",
                        "saveMethod":"ajax",
                        "toggleActive":true,
                        "enqueueFrontend":"1",
                        "enqueueBuilder":"1",
                        "enqueueGutenberg":"1",
                        "hasChanged": true,
                        "at_framework":true,
                        "at_version": "1.0.0"
                    },
                    {
                        "id":"at_framework_overrides",
                        "file":false,
                        "label":"overrides.css",
                        "type":"css",
                        "typeLabel":"css",
                        "category":"at framework",
                        "scope":"global",
                        "message":"",
                        "readOnly":"",
                        "contentCss":"/*******\n\nOverrides declared in this stylesheet won’t be overwritten during updates, so your custom styles are safe. This file can only be deleted manually and it’s disabled by default\n\n********/",
                        "contentSass":"",
                        "order":5,
                        "status":"0",
                        "priority":"10",
                        "saveMethod":"ajax",
                        "toggleActive":true,
                        "enqueueFrontend":"1",
                        "enqueueBuilder":"1",
                        "enqueueGutenberg":"1",
                        "hasChanged": true
                    }
                ]',
            "recipes" => '
                [
                    {
                        "id": "recipe-button",
                        "file": false,
                        "label": "button",
                        "type": "css",
                        "typeLabel": "recipe",
                        "category": "at framework",
                        "message": "",
                        "readOnly": false,
                        "contentCss": "/*\nButton overrides for variations\nDelete the variables you do not want to override\nSet the values you want. \n*/\n\n%root%.bricks-button.bricks-button,\n%root% .bricks-button.bricks-button{\n    /* Global properties */\n    --at-btn-border-radius: var(--at-radius--s);\n    --at-btn-border-radius--hover: var(--at-btn-border-radius);  \n    --at-btn-border-width: 2px;\n    --at-btn-border-width--hover: var(--at-btn-border-width);\n    --at-btn-shadow: none;\n    --at-btn-shadow--hover: var(--at-btn-shadow--l);\n    --at-btn-transition: all var(--at-duration--fast) ease;\n    /* Outline properties */\n    --at-btn-outline-border-radius: var(--at-radius--s);\n    --at-btn-outilne-border-radius--hover: var(--at-btn-border-radius);  \n    --at-btn-outline-border-width: 2px;\n    --at-btn-outline-border-width--hover: var(--at-btn-border-width);\n    --at-btn-outline-shadow: none;\n    --at-btn-outline-shadow--hover: var(--at-btn-shadow--l);\n    /* Variant properties - Primary Color example */\n    --at-btn-primary-color: var(--at-primary-l-6);\n    --at-btn-primary-color--hover: var(--at-primary-l-6);\n    --at-btn-primary-background: var(--at-primary);\n    --at-btn-primary-background--hover: var(--at-primary-d-1);\n    --at-btn-primary-border-color: var(--at-primary);\n    --at-btn-primary-border-color--hover: var(--at-primary-d-1);\n    /* Variant properties - Outline example */\n    --at-btn-primary-outline-color: var(--at-primary);\n    --at-btn-primary-outline-color--hover: var(--at-primary-d-1);\n    --at-btn-primary-outline-background: rgba(0,0,0,0);\n    --at-btn-primary-outline-background--hover: rgba(0,0,0,0)\n    --at-btn-primary-outline-border-color: var(--at-primary);\n    --at-btn-primary-outline-border-color--hover: var(--at-primary-d-1);\n    /* Variant properties - Medium Size example */\n    --at-btn-medium-font-size: var(--at-text--s);\n    --at-btn-medium-padding-block: .5em;\n    --at-btn-medium-padding-inline: 1em;\n    /* The following properties have no corresponding variables and need to be overwritten the hard way */\n    font-style: normal;\n    font-weight: 600;\n    letter-spacing: 0.2px;\n    line-height: 1;\n    text-align: center;\n    text-decoration: none;\n    text-transform: uppercase;\n    text-wrap: nowrap;\n    white-space: nowrap;\n}",
                        "status": "1",
                        "saveMethod": "ajax",
                        "toggleActive": true,
                        "hasChanged": true,
                        "at_framework": "true",
                        "at_version": "1.0.0"
                    },
                    {
                        "id": "recipe-colorset",
                        "file": false,
                        "label": "colorset",
                        "type": "css",
                        "typeLabel": "recipe",
                        "category": "at framework",
                        "message": "",
                        "readOnly": false,
                        "contentCss": "%root% {\n  --text-color: var(--at-neutral-d-5);\n  --background: var(--at-neutral-l-5);\n  --heading-color: var(--at-neutral-d-6);\n  \n  --link-color:  var(--at-link-color);\n  --link-color--hover: var(--at-link-color--hover);\n  --link-color--active: var(--at-link-color--active);\n  \n  --header-wrapper-color: var(--at-neutral-d-6);\n  --header-wrapper-background: var(--at-black-t-5);\n\n  --body-wrapper-color: var(--at-neutral-l-5);\n  --body-wrapper-background: var(--at-black-t-5);\n\n  --footer-wrapper-color: var(--at-neutral-d-6);\n  --footer-wrapper-background: var(--at-black-t-5);\n\n  --media-wrapper-color: var(--at-neutral-d-6);\n  --media-wrapper-background: var(--at-black-t-5);\n}",
                        "status": "1",
                        "saveMethod": "ajax",
                        "toggleActive": true,
                        "hasChanged": true,
                        "at_framework": "true",
                        "at_version": "1.0.0"
                    },
                    {
                        "id": "recipe-line-clamp",
                        "file": false,
                        "label": "line-clamp",
                        "type": "css",
                        "typeLabel": "recipe",
                        "category": "at framework",
                        "message": "",
                        "readOnly": false,
                        "contentCss": "%root%{\\n  --lines: 1;\\n}",
                        "status": "1",
                        "saveMethod": "ajax",
                        "toggleActive": true,
                        "hasChanged": true,
                        "at_framework": "true",
                        "at_version": "1.0.0"
                    },
                    {
                        "id": "recipe-ul-svg-icon",
                        "file": false,
                        "label": "ul-svg-icon",
                        "type": "css",
                        "typeLabel": "recipe",
                        "category": "at framework",
                        "message": "",
                        "readOnly": false,
                        "contentCss": "%root%{\\n  --item-gap:  0.25em;\\n  --font-size: var(--at-text--s);\\n  --icon-gap: 0.5em;\\n\\t--image-mask: var(--at-svg--arrow);\\n  --icon-color: var(--at-primary);\\n  --icon-offset: 0.3em;\\n  --icon-size: 1.2em;\\n}",
                        "status": "1",
                        "saveMethod": "ajax",
                        "toggleActive": true,
                        "hasChanged": true,
                        "at_framework": "true",
                        "at_version": "1.0.0"
                    },
                    {
                        "id": "recipe-mark",
                        "file": false,
                        "label": "mark",
                        "type": "css",
                        "typeLabel": "recipe",
                        "category": "at framework",
                        "message": "",
                        "readOnly": false,
                        "contentCss": "%root%{\\n  --color: var(--at-mark-color);\\n  --line-height: var(--at-mark-line-height);\\n  --padding: var(--at-mark-padding);  \\n  --text-shadow: var(--at-mark-text-shadow);\\n  --font-size: var(--at-mark-font-size);\\n  --font-weight: var(--at-mark-font-weight);\\n  --text-transform: var(--at-mark-text-transform);\\n  --letter-spacing: var(--at-mark-letter-spacing);\\n  --transform: var(--at-mark-transform);\\n  \\n  --background: var(--background, var(--at-mark-background));\\n  --box-shadow: var(--at-mark-shadow);\\n  --border: var(--at-mark-border-width) solid var(--at-mark-border-color);\\n  --border-radius: var(--at-mark-border-radius);\\n  --background-transform: var(--at-mark-background-transform);\\n  --inset-block, var(--at-mark-inset-block);\\n  --inset-inline, var(--at-mark-inset-inline);\\n}\\n",
                        "status": "1",
                        "saveMethod": "ajax",
                        "toggleActive": true,
                        "hasChanged": true,
                        "at_framework": "true",
                        "at_version": "1.0.0"
                    },
                    {
                        "id": "recipe-media-bp-up",
                        "file": false,
                        "label": "media-bp-up",
                        "type": "css",
                        "typeLabel": "recipe",
                        "category": "at framework",
                        "message": "",
                        "readOnly": false,
                        "contentCss": "@media(min-width: 768px){\\n  %root%{\\n    color: red; /* your rules */\\n  }\\n}",
                        "status": "1",
                        "saveMethod": "ajax",
                        "toggleActive": true,
                        "hasChanged": true,
                        "at_framework": "true",
                        "at_version": "1.0.0"
                    },
                    {
                        "id": "recipe-media-bp-down",
                        "file": false,
                        "label": "media-bp-down",
                        "type": "css",
                        "typeLabel": "recipe",
                        "category": "at framework",
                        "message": "",
                        "readOnly": false,
                        "contentCss": "@media(max-width: 767px){\\n  %root%{\\n    color: red; /* your rules */\\n  }\\n}",
                        "status": "1",
                        "saveMethod": "ajax",
                        "toggleActive": true,
                        "hasChanged": true,
                        "at_framework": "true",
                        "at_version": "1.0.0"
                    },
                    {
                        "id": "recipe-container-wrapper",
                        "file": false,
                        "label": "container-wrapper",
                        "type": "css",
                        "typeLabel": "recipe",
                        "category": "at framework",
                        "message": "",
                        "readOnly": false,
                        "contentCss": "%root% {\\n\\tcontainer-type: inline-size;\\n  container-name: your-container-name;\\n}",
                        "status": "1",
                        "saveMethod": "ajax",
                        "toggleActive": true,
                        "hasChanged": true,
                        "at_framework": "true",
                        "at_version": "1.0.0"
                    },
                    {
                        "id": "recipe-container-query",
                        "file": false,
                        "label": "container-query",
                        "type": "css",
                        "typeLabel": "recipe",
                        "category": "at framework",
                        "message": "",
                        "readOnly": false,
                        "contentCss": "/* Now use a container query */\\n@container your-container-name (min-width: 400px) {\\n  %root%{\\n    color: red; /* your rules */\\n  }\\n}",
                        "status": "1",
                        "saveMethod": "ajax",
                        "toggleActive": true,
                        "hasChanged": true,
                        "at_framework": "true",
                        "at_version": "1.0.0"
                    },
                    {
                        "id": "recipe-sticky-header-height",
                        "file": false,
                        "label": "sticky-header-height",
                        "type": "css",
                        "typeLabel": "recipe",
                        "category": "at framework",
                        "message": "",
                        "readOnly": false,
                        "contentCss": "/*******\\nSTICKY HEADERS\\n\\nSet out header height variable based on the viewport width\\n\\nRECOMMENDATION: \\nComment out the following media queries. Use the JavaScript found here https://gist.github.com/wpeasy/66995b6b153edd8142dd4646df41cd19\\n\\nBy using JS the header height is measured constantly as the screen with changes. This is more accurate that just changing it manually at breakpoints.\\n\\n********/\\n\\n:root{ --at-header-height: 200px; /* default for 991 and above */ }\\n@media(max-width: 467px){\\n  :root{ --at-header-height: 200px; /* measure manually */}\\n}\\n@media(max-width: 776px){\\n  :root{ --at-header-height: 200px; /* measure manually */}\\n}\\n@media(max-width: 990px){\\n  :root{ --at-header-height: 200px; /* measure manually */}\\n}\\n\\n/* If the Header is sticky, add extra padding to the first content section */\\n#brx-header.sticky:not(.on-scroll) ~ #brx-content > *:first-of-type{\\n  padding-block-start: calc(var(--at-section-padding-block) + var(--at-header-height));\\n}",
                        "status": "1",
                        "saveMethod": "ajax",
                        "toggleActive": true,
                        "hasChanged": true,
                        "at_framework": "true",
                        "at_version": "1.0.0"
                    },
                    {
                        "id": "recipe-bricks-form",
                        "file": false,
                        "label": "bricks-form",
                        "type": "css",
                        "typeLabel": "recipe",
                        "category": "at framework",
                        "message": "",
                        "readOnly": false,
                        "contentCss": "%root%{\\n  /*####\\n  Overrides for .at-bricks-form-styled class only \\n  ###*/\\n  \\n  /* Checkbox and Radio Markers */\\n  --icon-gap: var(--at-space--xs);\\n  --icon-background: var(--at-form-option-on-color);\\n  --icon-background--checked: var(--at-form-option-off-color);\\n  --icon-size: 1.2em;\\n  --icon-offset: .3em;\\n  \\n  /* Checkbox */\\n  --checkbox-svg: var(--at-svg-cancel-solid);\\n  --checkbox-checked-svg: var(--at-svg-check-solid);\\n\\n  /* Radio */\\n  --radio-svg: var(--at-svg-radio-unselected);\\n  --radio-checked-svg: var(--at-svg-radio-selected);\\n\\n  /* Validity */\\n  --valid-color: var(--at-form-valid-color);\\n  --invalid-color: var(--at-form-invalid-color);\\n  \\n  /*####\\n  Overrides for AFT Variables applied to the Theme\\n  ###*/  \\n  --at-form-label-font-size: var(--at-text--s);\\n  --at-form-label-color: var(--at-neutral-d-4);\\n  --at-form-placeholder-font-size: var(--at-text--s);\\n  --at-form-placeholder-color: var(--at-black-t-4);\\n  --at-form-input-font-size: var(--at-text--s);\\n  --at-form-input-color: var(--at-neutral-d-3);\\n  --at-form-input-color--focus: var(--at-neutral-d-6);\\n  --at-form-input-background-color: var(--at-white);\\n  --at-form-input-background-color--focus: var(--at-neutral-l-5);\\n  --at-form-input-border-width: 2px;\\n  --at-form-input-border-color: var(--at-black-t-5);\\n  --at-form-input-border-radius: var(--at-radius--xs);\\n  --at-form-input-border-radius--focus: var(--at-radius--s);\\n  --at-form-input-padding-inline: 0.7em;\\n  --at-form-input-padding-block: 0.4em;\\n  --at-form-group-padding-inline: var(--at-space--xs);\\n  --at-form-group-padding-block: var(--at-space--s);\\n  --at-form-option-color: var(--at-primary-l-5);\\n  --at-form-option-background: var(--at-primary-d-3);\\n  --at-form-focus-outline-width: 2px;\\n  --at-form-option-off-color: var(--at-neutral);\\n  --at-form-option-on-color: var(--bricks-text-success); \\n  \\n  /*\\n  Note: Most typography settings are done directly in the  Bricks Settings UI\\n  To override these, you need to add the CSS properties directly here\\n  */\\n}",
                        "status": "1",
                        "saveMethod": "ajax",
                        "toggleActive": true,
                        "hasChanged": true,
                        "at_framework": "true",
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "recipe-bricks-form-inline",
                        "file": false,
                        "label": "bricks-form-inline",
                        "type": "css",
                        "typeLabel": "recipe",
                        "category": "at framework",
                        "message": "",
                        "readOnly": false,
                        "contentCss": "%root%{\\n  --gap: 0;\\n  --border-radius: var(--at-form-input-border-radius);\\n  --grid-template-column: var(--at-grid--1) auto;\\n}",
                        "status": "1",
                        "saveMethod": "ajax",
                        "toggleActive": true,
                        "hasChanged": true,
                        "at_framework": "true",
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "recipe-placeholder",
                        "file": false,
                        "label": "placeholder",
                        "type": "css",
                        "typeLabel": "recipe",
                        "category": "at framework",
                        "message": "",
                        "readOnly": false,
                        "contentCss": "%root% {\\n\\t--background-color: var(--at-neutral-t-5);\\n  --border: 2px dashed var(--at-neutral-t-4);\\n  --border-radius: var(--at-radius--s);\\n  --padding: var(--at-space--s);\\n  --min-height: 90px;\\n  \\n  --label-background-color: var(--at-neutral-t-3);\\n  --label-color: var(--at-white);\\n  --label-padding: 4px;\\n  --label-padding: 3px;\\n  \\n  --icon-size: 2rem;\\n  --icon-opacity: 0.5 ;\\n  --icon-svg: /* your svg url */;\\n}",
                        "status": "1",
                        "saveMethod": "ajax",
                        "toggleActive": true,
                        "hasChanged": true,
                        "at_framework": "true",
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "recipe-entrance-animations",
                        "file": false,
                        "label": "entrance-animations",
                        "type": "css",
                        "typeLabel": "recipe",
                        "category": "at framework",
                        "message": "",
                        "readOnly": false,
                        "contentCss": "%root% {\n  /* Global animation Settings */\n  --animation-name: animationDefault;\n  --animation-distance: var(--at-entrance-animation--distance);\n  --animation-easing: var(--at-entrance-animation--easing);\n  --animation-image-easing: var(--at-entrance-animation--image-easing);\n  --animation-layer-easing: var(--at-entrance-animation--layer-easing);\n  --animation-delay: var(--at-entrance-animation--delay);\n  --animation-delay-multiplier: 0;\n  --animation-duration: var(--at-entrance-animation--duration);\n  --animation-fill-mode: forwards;\n  \n  /* Reveal Settings */\n  --reveal-clip-path: polygon(0 0, 100% 0, 100% 0, 0 0);\n  --reveal-duration: var(--at-entrance-animation-reveal--duration);\n  \n  /* Reveal With Layers */\n  --reveal-with-layer-duration: var(--at-entrance-animation-reveal-with-layer--duration);\n  --layer-bg-color: var(--at-entrance-animation--layer-bg-color);\n  --layer-animation: revealLayerAnimation;\n  --layer-transform-origin--start: 0% 0%;\n  --layer-transform-origin--end: 100% 0%;\n  --layer-scale--start: 1,0,1;\n}",
                        "status": "1",
                        "saveMethod": "ajax",
                        "toggleActive": true,
                        "hasChanged": true,
                        "at_framework": "true",
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "recipe-keyframes-animations",
                        "file": false,
                        "label": "keyframes-animations",
                        "type": "css",
                        "typeLabel": "recipe",
                        "category": "at framework",
                        "message": "",
                        "readOnly": false,
                        "contentCss": "/* bounce-in animation */\\n%root% {\\n  --animation-name: bounceInAnimation;\\n  --animation-fill-mode: both;\\n}\\n\\n/* fade-in animation */\\n%root% {\\n  --translate: 0, 0, 0;\\n}\\n\\n/* flip animation */\\n%root% {\\n  --animation-name: flipAnimation;\\n  --rotate-x: -180deg;\\n  --animation-easing: cubic-bezier(0.250, 0.460, 0.450, 0.940);\\n  --animation-fill-mode: both;\\n}\\n\\n/* puff-in animation */\\n%root% {\\n  --scale: 2;\\n  --blur: 4px;\\n  --animation-name: puffInAnimation;\\n  --animation-fill-mode: both;\\n  --animation-easing: cubic-bezier(0.470, 0.000, 0.745, 0.715);\\n}\\n\\n/* roll-in animation */\\n%root% {\\n  --translate-x: -800px;\\n  --rotate: -540deg;\\n  --animation-name: rollInAnimation;\\n  --animation-easing: ease-out;\\n  --animation-fill-mode: both;\\n}\\n\\n/* rotate-scale animation */\\n%root% {\\n  --rotate-scale: 2;\\n  --rotate-z--half: 180deg;\\n  --rotate-z--end: 360deg;\\n  --animation-name: rotateScaleAnimation;\\n  --animation-fill-mode: both;\\n}\\n\\n/* rotate-1 animation */\\n%root% {\\n  --rotate: 0;\\n  --animation-name: rotate1Animation;\\n  --animation-easing: cubic-bezier(0.250, 0.460, 0.450, 0.940);\\n  --animation-fill-mode: both;\\n}\\n\\n/* rotate-2 animation */\\n%root% {\\n  --rotate: -45deg;\\n  --animation-name: rotate2Animation;\\n  --animation-easing: cubic-bezier(0.250, 0.460, 0.450, 0.940);\\n  --animation-fill-mode: both;\\n}\\n\\n/* scale-down animation */\\n%root% {\\n  --scale: 2;\\n  --animation-name: scaleDownAnimation;\\n  --animation-easing: cubic-bezier(0.250, 0.460, 0.450, 0.940);\\n  --animation-fill-mode: both;\\n}\\n\\n/* scale-up animation */\\n%root% {\\n  --scale: 0;\\n  --animation-name: scaleUpAnimation;\\n  --animation-easing: cubic-bezier(0.250, 0.460, 0.450, 0.940);\\n  --animation-fill-mode: both;\\n}\\n\\n/* slide aniimation - bottom to top */\\n%root% {\\n  --translate: 0, var(--animation-distance), 0;\\n}\\n\\n/* slide aniimation - left to right */\\n%root% {\\n  --translate: calc(var(--animation-distance) * -1), 0, 0;\\n}\\n\\n/* slide aniimation - right to left */\\n%root% {\\n  --translate: var(--animation-distance, 15px), 0, 0;\\n}\\n\\n/* slide aniimation - top to bottom */\\n%root% {\\n  --translate: 0, calc(var(--animation-distance, 15px) * -1), 0;\\n}\\n\\n/* slit in animation */\\n%root% {\\n  --translate-z--start: -800px;\\n  --rotate-y--start: 90deg;\\n  --translate-z--half: -160px;\\n  --rotate-y--half: 87deg;\\n  --animation-name: slitInAnimation;\\n  --animation-easing: ease-out;\\n  --animation-fill-mode: both;\\n}\\n\\n/* swing-in animation */\\n%root% {\\n  --rotate-x: -100deg;\\n  --origin: top;\\n  --animation-name: swingInAnimation;\\n  --animation-easing: cubic-bezier(0.175, 0.885, 0.320, 1.275);\\n  --animation-fill-mode: both;\\n}\\n\\n/*  swirl-in animation */\\n%root% {\\n  --rotate: -540deg;\\n  --scale: 0;\\n  --animation-name: swirlInAnimation;\\n  --animation-easing: ease-out;\\n  --animation-fill-mode: both;\\n}\\n\\n/* tilt-in animation */\\n%root% {\\n  --rotate-y: 30deg;\\n  --translate-y: -300px;\\n  --skew-y: -30deg;\\n  --animation-name: tiltinAnimation;\\n  --animation-easing: cubic-bezier(0.250, 0.460, 0.450, 0.940);\\n  --animation-fill-mode: both;\\n}",
                        "status": "1",
                        "saveMethod": "ajax",
                        "toggleActive": true,
                        "hasChanged": true,
                        "at_framework": "true",
                        "at_version": "1.1.0"
                    },
                    {
                        "id": "recipe-reveal-animations",
                        "file": false,
                        "label": "reveal-animations",
                        "type": "css",
                        "typeLabel": "recipe",
                        "category": "at framework",
                        "message": "",
                        "readOnly": false,
                        "contentCss": "/* reveal - bottom to top */\\n%root% {\\n  --animation-name: revealBottomAnimation;\\n  --animation-easing: cubic-bezier(0.77, 0, 0.175, 1);\\n  --clip-path: polygon(0 100%, 100% 100%, 100% 100%, 0 100%);\\n  --translate: 0, 0, 0;\\n}\\n\\n/* reveal - left to right */\\n%root% {\\n  --animation-name: revealLeftAnimation;\\n  --animation-easing: cubic-bezier(0.77, 0, 0.175, 1);\\n  --clip-path: polygon(0 0, 0 0, 0 100%, 0% 100%);\\n  --translate: 0, 0, 0;\\n}\\n\\n/* reveal - right to left */\\n%root% {\\n  --animation-name: revealRightAnimation;\\n  --animation-easing: cubic-bezier(0.77, 0, 0.175, 1);\\n  --clip-path: polygon(100% 0, 100% 0, 100% 100%, 100% 100%);\\n  --translate: 0, 0, 0;\\n}\\n\\n/* reveal - top to bottom */\\n%root% {\\n  --animation-name: revealTopAnimation;\\n  --animation-easing: cubic-bezier(0.77, 0, 0.175, 1);\\n  --animation-duration: var(--at-entrance-animation-reveal--duration);\\n  --clip-path: polygon(0 0, 100% 0, 100% 0, 0 0);\\n  --translate: 0, 0, 0;\\n}\\n\\n\\n/* reveal with layer - bottom to top */\\n%root% {\\n  --layer-animation: layerRevealBottom;\\n  --animation-name: unset;\\n  --transform-origin: 100% 100%;\\n  --translate: 0, 0, 0;\\n}\\n\\n/* reveal with layer - left to right */\\n%root% {\\n  --layer-animation: layerRevealLeft;\\n  --transform-origin: 0% 0%;\\n  --animation-name: unset;\\n  --translate: 0, 0, 0;\\n}\\n\\n/* reveal with layer - right to left */\\n%root% {\\n  --layer-animation: layerRevealRight;\\n  --transform-origin: 100% 100%;\\n  --animation-name: unset;\\n  --translate: 0, 0, 0;\\n}\\n\\n/* reveal with layer - top to bottom */\\n%root% {\\n  --layer-animation: layerRevealTop;\\n  --transform-origin: 0% 0%;\\n  --animation-name: unset;\\n  --translate: 0, 0, 0;\\n}",
                        "status": "1",
                        "saveMethod": "ajax",
                        "toggleActive": true,
                        "hasChanged": true,
                        "at_framework": "true",
                        "at_version": "1.1.0"
                    }
                ]'
        ]
    ];

    public static function manage_stagger_class( $attributes, $key, $element ) {
        if ( $key !== '_root' ) {
            return $attributes;
        }
    
        if ( ! is_object( $element ) || ! isset( $element->element ) || ! is_array( $element->element ) ) {
            return $attributes;
        }
    
        $element_data = $element->element;
        $element_settings = $element_data['settings'] ?? [];
        $element_global_classes = $element_settings['_cssGlobalClasses'] ?? [];
    
        if ( ! is_array( $element_global_classes ) || empty( $element_global_classes ) ) {
            return $attributes;
        }
    
        $trigger_classes = [ 'at_anim-stagger' ];
        $has_trigger_class = ! empty( array_intersect( $trigger_classes, $element_global_classes ) );
    
        if ( ! $has_trigger_class ) {
            return $attributes;
        }
    
        $element_children = $element_data['children'] ?? [];
    
        if ( ! is_array( $element_children ) || empty( $element_children ) ) {
            return $attributes;
        }
    
        $index = 0;
    
        foreach ( $element_children as $child_id ) {
            add_filter( 'bricks/element/render_attributes', function( $attributes, $key, $element ) use ( $child_id, &$index ) {
                if ( $key !== '_root' || ! is_object( $element ) || $element->id !== $child_id ) {
                    return $attributes;
                }
    
                if ( isset( $attributes['_root']['style'] ) ) {
                    $attributes['_root']['style'] = rtrim( $attributes['_root']['style'], ';' ) . ";--at-index:{$index};";
                } else {
                    $attributes['_root']['style'] = "--at-index:{$index};";
                }
    
                $index++;
                return $attributes;
            }, 10, 3 );
        }
    
        return $attributes;
    }
}