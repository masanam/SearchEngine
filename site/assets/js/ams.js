/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery(document).ready(function($) 
{ 
    
    $('#amsHide').click(function()
    {
        $('#amsLeftPanel').hide('slow');
        $('#amsShow').show('slow');
        $('#amsRightPanel').removeClass('span-16');
        
    //$('#amsRightPanel').addClass('span-20');
        
    });
    $('#amsShow').click(function()
    {
        $('#amsShow').hide('slow');
        $('#amsLeftPanel').show('slow');
        $('#amsRightPanel').removeClass('span-20');
        $('#amsRightPanel').addClass('span-16');
        
    });
        
    $('.question').click(function() {
 
        if($(this).next().is(':hidden') != true) {
            $(this).removeClass('active'); 
            $(this).next().slideUp("normal");
        } else {
            //$('.question').removeClass('active');  
            //$('.answer').slideUp('normal');
            if($(this).next().is(':hidden') == true) {
                $(this).addClass('active');
                $(this).next().slideDown('normal');
            }   
        }
    });
 
    //$('.answer').hide();
 
    $('.expand').click(function(event)
    {
        $('.question').next().slideDown('normal');

        {
            $('.question').addClass('active');
        }
    });
 
    $('.collapse').click(function(event)
    {
        $('.question').next().slideUp('normal');

        {
            $('.question').removeClass('active');
        }
    });
        
        
});