# 1.2.12
* fixed single select element if it's `null`

# 1.2.11
* Der Autor kann im Listing durch die Plugin Konfiguration aktiviert oder deaktiviert werden
* Twig Block `sas_blog_card_footer` und `sas_blog_card_footer_author` hinzugefügt
* Styling Fehler bei leeren Placeholder Autor Image behoben

# 1.2.10
* Storefront lokales datum fix
* Autor in der Storefront hinzugefügt
* Generelle Überarbeitung des Stylings im Listing

# 1.2.9
* Teaser Thumbnail Größe angepasst

# 1.2.8
* Strukturierte Daten Autor Fehler behoben
* read more title der Beitrags ersetzt durch title

# 1.2.7
* Fehler behoben, welcher auftrat, wenn man das Blog listing + Blog detail Element auf derselben CMS Seite hat
* Überflüssigen Twig Filter entfernt
* Single Select Data Resolver verschoben

# 1.2.6
* Single Select blog CMS Element & Block verbessert

#1.2.5
* Datumsausgabe im Blog Box Template angepasst

# 1.2.4
* Es können nun auch Kategorien in einer non-system Sprache (Default Sprache welche während der Installation angegeben wird) übersetzt werden

# 1.2.3
* Es wird nun dir Systemsprache beim erstellen eines Artikels geprüft, welches zuvor zu Fehlern führte,
da Einträge immer zuerst in der Systemsprache angelegt werden müssen.

# 1.2.2
* Fehler der Übersetzung der Kategorie im Twig Template behoben

# 1.2.1
* editor.js entfernt
* fehlende Beschreibung Übersetzung hinzugefügt

# 1.2.0
* Replaced editor.js with Shopware's default text editor
* Added `Blog Single Select` CMS element, to show a single blog entry card
* Added counter for meta title and description
* Fixed JSON-LD SEO template
* Prepared plugin base for the Blog PRO version

# 1.1.5
* Added missing German author translations

# 1.1.4
* Added author entity
* Added category entity

# 1.1.3
* changed `SalesChannelCmsPageLoader` to `SalesChannelCmsPageLoaderInterface` in `BlogController`

# 1.1.2
* fixed Blog Plugin kills all meta-Infos from all other pages #20
* fixed Stay on blogpost when saving #14
* fixed Error 500 when using «Raw HTML» in Editor #9

# 1.1.1
* fixed license to MIT

# 1.1.0
* Added teaser image within listing
* fixed error messages if title or slug is empty
* added publish date to listing card

# 1.0.0
* Erster Release im Store
