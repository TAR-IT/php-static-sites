# PHPages - a static website generator using PHP
This is a handy little tool for creating static sites using PHP. It is a consolidation of different snippets and ideas that I changed to my needs. My goal is to create a lightweight static site generator with SEO Tag implementations and multilanguage support.
## Table of Contents
1. [Technologies Used](#technologies-used)
2. [Getting Started](#getting-started)
    - [Installing](#installing)
    - [Usage](#usage)
    - [Testing](#testing)
    - [Contributing](#contributing)
3. [License](#license)
## Technologies Used
- [PHP](https://www.w3schools.com/php/)
- [HTML](https://www.w3schools.com/html/)
- [CSS](https://www.w3schools.com/css/)
- [JavaScript](https://www.w3schools.com/js/)
- [Bash](https://www.gnu.org/software/bash/manual/bash.html)
## Getting Started
### Installation
1. Download & install [PHP](https://www.php.net/downloads).
1. Fork/clone the repository or download the repository as a .zip folder and unzip it.
### Usage
1. Start building your pages with the help of templates  and includes
    - use templates to extend your pages with predefined html (default directory is "src/templates/")
        - pages can extend a template by calling the following function in your page:
            ```php
            startExtend(string $template, array $variables)
            ```
            ```php
            endExtend()
            ```
        - This will extend the content of your page with the corresponding template file. Example usage in your .html file (where "default.html" is a template in "src/templates/"):
            ```html
            <?php startExtend("default.html", []); ?>
                <main>
                    <h1>This is the page content</h1>
                </main>
            <?php endExtend(); ?>
            ```
    - use includes to include blocks of html in your pages (default directory is "src/includes/")
        - includes can be used to extend pages, templates or other includes:
            ```php
            includePart(string $include, array $variables, bool $print)
            ```
        - This will extend the content of your page with the corresponding template file. Example usage in your .html file (where "nav.html" is a file in "/src/includes/"):
            ```html
            <?php includePart("nav.html", []); ?> 
            ```
2. Use enviroment variables for staging and production environments
    - the files "env.prod.php" and "env.stage.php" are used for enviromental variables
    - building the website will default to "env.stage.php" - using the "--prod" tag while building will switch to production variables ( in "env.prod.php")
3. Generate the static sites by using the build script via the terminal - the output files will be placed in "public/"
    ```bash
    php build.php # for staging variables
    ```
    or
    ```bash
    php build.php --prod # for production variables 
    ```

### Testing
No tests have been applied yet.
### Contributing
Contributing is not intendet yet.
## License
This project is licensed under [GNU General Public License v3](https://www.gnu.org/licenses/gpl-3.0.de.html).
