function showtip() {
    tt = $(this).siblings('div.tooltip');
    if (tt.css('display') == 'none')
    {
        $('div.tooltip').css('display','none');
        $('div.item > span').removeClass('highlight');

        tt.css('display','block');
        $(this).addClass('highlight');

        if (tt.children().children('code').width() > 270)
        {
            tt.width(tt.children().children('code').width());
        }
        
    } else {
        $('div.tooltip').css('display','none');
        $('div.item > span').removeClass('highlight');
    }

}

$(document).ready(function(){
    //$('.ko3class').addClass("dontsplit");
    $('#cs').columnize({width:260});
        
    $('div.item > span').click(showtip);
    
    $('div.closebtn').click(function() {
        $('div.tooltip').css('display','none');
        $('div.item > span').removeClass('highlight');
    });
});

$(window).resize(function() {
    
    id = window.setTimeout(function() {
        $('div.item > span').click(showtip);
        
        $('div.closebtn').click(function() {
            $('div.tooltip').css('display','none');
            $('div.item > span').removeClass('highlight');
        });
        
        window.clearTimeout(id);
        
    }, 1000);
    
})