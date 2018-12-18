// JavaScript Document
    jQuery(document).ready(function($)
    {
        $('#delete').click(function()
        {
            $('#submitForm').trigger('click');
        });

        $("#MySplitter").splitter({
            sizeLeft: 407, minRight: 400,
            dock: "left",
            dockSpeed: 200
        });
    });
