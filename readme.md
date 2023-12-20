# Laravel Dynamic Datatable

Laravel Dynamic Datatable is a package that simplifies the integration of dynamic datatables into your Laravel application using Blade directives. It provides features like export buttons, column visibility toggling, search functionality, custom rendering, and class assignment to columns.

## Features

- Export Button
- Column Visibility
- Search Input
- Custom Render Function
- Custom Class Assignment to Column


## Installation

Install the package using Composer:

```bash
composer require artflow-studio/laravel-dynamic-datatable
```
Publish the package assets
```bash
php artisan vendor:publish --tag=AF_dtable --force
```
## Include Styles and Scripts
Add the following stack directives in your Blade template to include the required styles and scripts:
```bash
<head>
    <!-- Other head elements -->

    @stack('AF_dtable.css')
</head>
```
At the end of the body tag:
```bash
<body>
    <!-- Your HTML content -->

    @stack('AF_dtable.js')
</body>
```
## Blade Directives Usage
- Header for Search Export and Column Visibility Buttons
```bash
@AF_dtable_btns(['search' => true, 'colvis' => true, 'export_btn' => true])
```
- HTML Code for Table (must below the AF_dtable_btns)
```
    <table id="myTable">
        <thead>
            <tr>
                <th>Col1</th>
                <th>Col2</th>
                <th>Col3</th>
            </tr>
        </thead>
    </table>
```
- DataTable Initialization Code
```
@AF_dtable([   
    'id' => 'myTable',                      //the id of the table 
    'route' => 'my.route.name',             //Route name from Web.php
    'cols' => ['col1' , 'col2', 'col3'],    // column names from the controller
        ])
```

 Other Options (Optional)
```
'info' => 'true',
'processing' => 'true',
'serverside' => 'true',
'select' => 'true',
'hide_cols' => [0,1,2,3,4,5],
'cols_class' => [
    ['0','your-class-name'],['1','your-class-name'],
    ],
'render' => [
            //Returning Column Data in a Mailto Anchor Tag 
            // "${data}" is the actual data returning from Database  
        ['col' => 5, 'html' => '<a href="mailto:${data}">${data}</a>']
        ],
```
 
## Authors

- [@RaHee554](https://www.github.com/rahee554)

