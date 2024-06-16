require(['jquery', 'uiRegistry'], function ($, uiRegistry) {
    // Your custom JavaScript code goes here
    $(document).ready(function () {
        // Example: Change the background color of a specific element
        console.log('i am from custom js file helloworld module');
        // Add more custom code as needed

        // Assuming 'your_grid_listing' is the name of your grid
        var gridSelector = '#page\\:main-container';
        
        // Attach a click event to the grid rows
        jQuery(gridSelector + ' tbody').on('click', 'tr', function () {
            // Get the value of the 'your_column_name' column
            var columnValue = jQuery(this).find('.data-grid-cell-content').text();
            
            // Do something with the retrieved value
            console.log('Value of your_column_name:', columnValue);
        });
    });    
});

