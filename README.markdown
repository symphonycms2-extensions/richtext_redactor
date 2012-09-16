# Rich Text Formatter (Redactor)

* Version: 0.9.0
* Author: Remie Bolte <http://github.com/remie>
* Build Date: 2012-09-16
* Requirements: Symphony 2.3

## Installation

1. Download the Rich Text Formatter (Redactor) extension and upload the 'richtext_redactor' folder to the 'extensions' folder of your Symphony instance.
2. Enable the extension by selecting "Rich Text (Redactor) Text Formatter" in the list and choose Enable from the with-selected menu, then click Apply.

## Warning

Uploaded images are stored in a folder named `redactor` in your `/workspace` folder.
You will need to alter your .htaccess file to allow your webserver to expose these files. This is required in order to show them in your text editor. Next versions of this extension will make this location configurable.

## Disclaimer

Unfortunately, the RedactorJS editor does not output strict XHTML. This means that the output was formatted prior to saving. The process of translating HTML to XHTML might cause issues. If this happens, please use the HTML formatting mode to adjust the output manually.

## Version History

### 0.9.0

* initial release of this extension, waiting impatiently for your feedback!