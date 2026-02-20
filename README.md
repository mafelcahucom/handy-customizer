# Handy Customizer Framework

![Version: V1.0.0](https://img.shields.io/badge/version-v1.0.0-blue.svg)
![License: GPL v3](https://img.shields.io/badge/license-GPLv3-orange.svg)
![Version: PHP](https://img.shields.io/badge/PHP-8.0.0+-%237b7fb5.svg)
![Version: WordPress](https://img.shields.io/badge/WordPress-4.9+-%2328799e.svg)

Welcome to the Handy Customizer Framework GitHub repository! Explore the source code, review open issues, and stay updated with ongoing development. For detailed guidance, best practices, and complete feature insights, we strongly recommend reading the [official documentation](https://www.gethandycustomizer.site).

## Description

Handy Customizer Framework is an open-source WordPress toolkit built to help WordPress Theme Developers to develop themes using the WordPress Customizer API while writing clean and minimal code only.

## Why You Should Use It?

- **Super Easy To Use** - The Customizer API can be a headache and confusing. So Handy managed to simplify the process of adding controls to customizer.
- **Avoid Code Repetition** - Handy will allow you to organize your code. Instead of calling 3 methods in order to add controls, Handy simplified it by just calling one method.
- **Helpful Utilities** - Handy provide also a useful API for utilities, This will provide a useful methods where you can use in adding controls and providing data.
- **Enhance Productivity** - Because Handy simplified Customizer API, it will help you to work fast, minimally, and save a lot of time coding complicated controls.

## Essential Components

- **[Panel](https://www.gethandycustomizer.site/docs/essentials/panel/)** - Panels serve as containers for sections and give you the option to arrange several sections together and add another level of hierarchy above controls and sections.
- **[Section](https://www.gethandycustomizer.site/docs/essentials/section/)** - Sections serve as containers for controls and give you the option to arrange several controls together. Also, sections can be added to the panel.
- **[Control](https://www.gethandycustomizer.site/docs/essentials/control/)** - Controls are the main Customizer object for building UI fields. Every control must be associated with a setting, and that setting will store user-entered data from the control as well as display and sanitize it in the live preview. Controls must be added to a section before they will be displayed (and sections must contain controls to be displayed).

## Getting Started

Hello there! Are you ready to to learn more about Handy? Let's first take a brief look at Handy and see how we can use it to our WordPress theme development projects.

- [Introduction](https://www.gethandycustomizer.site/docs/getting-started/introduction/)
- [Installation](https://www.gethandycustomizer.site/docs/getting-started/installation/)
- [Download](https://www.gethandycustomizer.site/docs/getting-started/download/)

## Available Controls

Below is a comprehensive list of all available [controls](https://www.gethandycustomizer.site/docs/controls/). Each control is designed to give you flexibility and precision, allowing you to customize and manage features with ease.

- [Audio Uploader](https://www.gethandycustomizer.site/docs/controls/audio-uploader/)
- [Alignment](https://www.gethandycustomizer.site/docs/controls/alignment/)
- [Angle Picker](https://www.gethandycustomizer.site/docs/controls/angle-picker/)
- [Button Set](https://www.gethandycustomizer.site/docs/controls/button-set/)
- [Checkbox](https://www.gethandycustomizer.site/docs/controls/checkbox/)
- [Checkbox Multiple](https://www.gethandycustomizer.site/docs/controls/checkbox-multiple/)
- [Checkbox Pill](https://www.gethandycustomizer.site/docs/controls/checkbox-pill/)
- [Code Editor](https://www.gethandycustomizer.site/docs/controls/code-editor/)
- [Color Picker](https://www.gethandycustomizer.site/docs/controls/color-picker/)
- [Color Set](https://www.gethandycustomizer.site/docs/controls/color-set/)
- [Content Editor](https://www.gethandycustomizer.site/docs/controls/content-editor/)
- [Counter](https://www.gethandycustomizer.site/docs/controls/counter/)
- [Dashicons](https://www.gethandycustomizer.site/docs/controls/dashicons/)
- [Date Picker](https://www.gethandycustomizer.site/docs/controls/date-picker/)
- [Dimension](https://www.gethandycustomizer.site/docs/controls/dimension/)
- [Dropdown Custom Post](https://www.gethandycustomizer.site/docs/controls/dropdown-custom-post/)
- [Dropdown Page](https://www.gethandycustomizer.site/docs/controls/dropdown-page/)
- [Dropdown Post](https://www.gethandycustomizer.site/docs/controls/dropdown-post/)
- [Duotune](https://www.gethandycustomizer.site/docs/controls/duotune/)
- [Email](https://www.gethandycustomizer.site/docs/controls/email/)
- [File Uploader](https://www.gethandycustomizer.site/docs/controls/file-uploader/)
- [Group](https://www.gethandycustomizer.site/docs/controls/group/)
- [Image Checkbox](https://www.gethandycustomizer.site/docs/controls/image-checkbox/)
- [Image Radio](https://www.gethandycustomizer.site/docs/controls/image-radio/)
- [Image Uploader](https://www.gethandycustomizer.site/docs/controls/image-uploader/)
- [Markup](https://www.gethandycustomizer.site/docs/controls/markup/)
- [Number](https://www.gethandycustomizer.site/docs/controls/number/)
- [Radio](https://www.gethandycustomizer.site/docs/controls/radio/)
- [Range](https://www.gethandycustomizer.site/docs/controls/range/)
- [Repeater](https://www.gethandycustomizer.site/docs/controls/repeater/)
- [Select](https://www.gethandycustomizer.site/docs/controls/select/)
- [Size](https://www.gethandycustomizer.site/docs/controls/size/)
- [Sortable](https://www.gethandycustomizer.site/docs/controls/sortable/)
- [Switch](https://www.gethandycustomizer.site/docs/controls/switch/)
- [Tagging](https://www.gethandycustomizer.site/docs/controls/tagging/)
- [Tagging Select](https://www.gethandycustomizer.site/docs/controls/tagging-select/)
- [Text](https://www.gethandycustomizer.site/docs/controls/text/)
- [Textarea](https://www.gethandycustomizer.site/docs/controls/textarea/)
- [Time Picker](https://www.gethandycustomizer.site/docs/controls/time-picker/)
- [Toogle](https://www.gethandycustomizer.site/docs/controls/toogle/)
- [URL](https://www.gethandycustomizer.site/docs/controls/url/)
- [Video Uploader](https://www.gethandycustomizer.site/docs/controls/video-uploader/)

## Arguments

There can be some confusion about the [arguments](https://www.gethandycustomizer.site/docs/arguments/), so to help clarify, we've included comprehensive and thorough documentation on each common argument.

- [active_callback](https://www.gethandycustomizer.site/docs/arguments/active_callback/)
- [default](https://www.gethandycustomizer.site/docs/arguments/default/)
- [description](https://www.gethandycustomizer.site/docs/arguments/description/)
- [id](https://www.gethandycustomizer.site/docs/arguments/id/)
- [label](https://www.gethandycustomizer.site/docs/arguments/label/)
- [panel](https://www.gethandycustomizer.site/docs/arguments/panel/)
- [placeholder](https://www.gethandycustomizer.site/docs/arguments/placeholder/)
- [priority](https://www.gethandycustomizer.site/docs/arguments/priority/)
- [sanitize_callback](https://www.gethandycustomizer.site/docs/arguments/sanitize_callback/)
- [section](https://www.gethandycustomizer.site/docs/arguments/section/)
- [title](https://www.gethandycustomizer.site/docs/arguments/title/)
- [validations](https://www.gethandycustomizer.site/docs/arguments/validations/)

## Utilities

In some cases we need to use WordPress Rest API to provide an internal data to our control, and we are aware that this will take some time. As a result, Handy offers helpful [utilities](https://www.gethandycustomizer.site/docs/utilities/) that you may utilize to feed data into your controls.

- [__get_categories](https://www.gethandycustomizer.site/docs/utilities/__get_categories/)
- [__get_custom_posts](https://www.gethandycustomizer.site/docs/utilities/__get_custom_posts/)
- [__get_image_sizes](https://www.gethandycustomizer.site/docs/utilities/__get_image_sizes/)
- [__get_material_colors](https://www.gethandycustomizer.site/docs/utilities/__get_material_colors/)
- [__get_pages](https://www.gethandycustomizer.site/docs/utilities/__get_pages/)
- [__get_post_types](https://www.gethandycustomizer.site/docs/utilities/__get_post_types/)
- [__get_posts](https://www.gethandycustomizer.site/docs/utilities/__get_posts/)
- [__get_tags](https://www.gethandycustomizer.site/docs/utilities/__get_tags/)
- [__get_taxonomies](https://www.gethandycustomizer.site/docs/utilities/__get_taxonomies/)
- [__get_terms](https://www.gethandycustomizer.site/docs/utilities/__get_terms/)
- [__get_users](https://www.gethandycustomizer.site/docs/utilities/__get_users/)

Hello World


