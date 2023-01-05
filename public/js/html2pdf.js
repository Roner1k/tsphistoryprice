jQuery(window).load(function ($) {

    var $ = jQuery;

    printButtonsInit();
    pdfButtons();

    function printButtonsInit() {
        let buttons = $('.print2pdf');

        $(buttons).each(function(){
            $(this).click(async function(e){
                let printID = $(this).attr('print-id');
                let printClass = $(this).attr('print-class');

                let printWrap = $('.' + printClass);
                    printWrap = printWrap[printID];

                let render = document.createElement('div');
                    $(render).addClass('print-render');


                let title = $(printWrap).find('.heading h4').html()

                let currentTitle = document.title;    
                document.title = title;

                await $('.print_hide').hide();

                await html2canvas($(printWrap)[0]).then(function (canvas) {
                    var imgData = canvas.toDataURL("image/jpeg", 1.0);
                    
                    $(render).html("<img id='Image' src=" + imgData + " style='width:100%;'></img>");
                    $(render).printThis();
                  
                    return 1;
                });

                await $('.print_hide').show();

                setTimeout(() => {
                    document.title = currentTitle;
                },500)


            })
        })
    }


    function pdfButtons() {
        let buttons = $('.getPDF');

        $(buttons).each(function(e){
            $(this).click(async function(){
                let printID = $(this).attr('pdf-id');
                let printClass = $(this).attr('pdf-class');

                let printWrap = $('.' + printClass);
                    printWrap = printWrap[printID];


                let HTML_Width = $(printWrap).width();
                let HTML_Height = $(printWrap).height();
                let top_left_margin = 35;
                let PDF_Width = HTML_Width + (top_left_margin * 2);
                let PDF_Height = HTML_Height + (top_left_margin * 2);
                let canvas_image_width = HTML_Width;
                let canvas_image_height = HTML_Height;        
                let title = $(printWrap).find('h4').html()
                let hideElements = $(printWrap).find('.print_hide');

                if($(this).hasClass('content-render')) {
                    title = $('title').html();
                }

                console.log(printWrap);
                await $(hideElements).hide();

                await html2canvas($(printWrap)[0]).then(function (canvas) {
                    var imgData = canvas.toDataURL("image/jpeg", 1.0);
                    var pdf = new jsPDF('p', 'pt', [PDF_Width, PDF_Height]);

                    console.log(pdf.internal.pageSize.height)
                    
                    if(PDF_Height > pdf.internal.pageSize.height) {
                        let page = Math.ceil(PDF_Height / pdf.internal.pageSize.height)
                        console.log(page)
                        for(var i = 0; i <= page - 1; i++) {
                            pdf.addPage();
                        }
                    }

                    pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin, canvas_image_width, canvas_image_height);
                    pdf.save(title + '.pdf');

                    return 1;
                });

                await $(hideElements).show();
            })
        })
    }


    

});


