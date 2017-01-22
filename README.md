# phpGlluchCoursera
 
phpGlluchMiriadaX is a collection of scripts to obtain 
the metadata from a group of 
[Coursera](https://www.coursera.org/) courses. 

Tested in january 2017

## Set up
The file courses.json with the list of all courses is needed.
I have gone to <https://api.coursera.org/api/catalog.v1/course> 
and copied pasted.

 ## Order of execution
 This files has to be executed in php CLI in this order:
 
 1. **php fetch.php**. Retrieve all courses.   
 2. **standarize.php**. Rename field names to share the 
 same titles as other related projects
 3. Get all the categories and the related courses 
 from <https://api.coursera.org/api/catalog.v1/categories?includes=courses>
 Separe every category of your interest in one file.
 3. **categories.php**. This include all the categories 
 in each json file.
 4. **lang_ordering**. 
 
 ## Result
 The courses information will be in 
 *lang_fix/en* directory for english 
 moocs and in *lang_fix/es* for spanish courses.
 
 ## Related
 
 phpGlluchEdX
 
 phpGlluchCourseTalk
 
 phpGlluchMiriadaX