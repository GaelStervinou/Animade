$(window).ready(function(){
    $('#menu-button').click(function(){
        $('#site-nav').toggleClass('open');
    });

    $(window).scroll(function(){
        if($(this).scrollTop() > 0){
            $('#site-header').addClass('sticky');
        }else{
            $('#site-header').removeClass('sticky');
        }
        var windowHeight = $(window).height();
        var windowScroll = $(this).scrollTop();
        $('main > section:not(#section1)').each(function(){
            var sectionTop = $(this).position().top;
            var offset = windowHeight - (sectionTop - windowScroll);
            if(offset >= windowHeight / 3 && $(this).css('opacity') == 0){
                $(this).css('opacity', 1);
                $(this).css('top', 0);
            }
        })
    });

});

/*
function initSlider(element){
    let container = $('<div/>')
        .addClass('slides-container')
        .html(element.html());
    element.html(container);
    element.find('img').addClass('slide');

    let nav = $('<nav/>')
        .append('<button class="prev"></button>')
        .append('<button class="next"></button>');
    element.append(nav);

    element.attr('data-currentSlide', 0);

    $('nav button').click(function(){
        if($(this).hasClass('next')){
            next(element);
        }else if($(this).hasClass('prev')){
            prev(element);
        }
    });
}

function next(element){
    let value = Number(element.attr('data-currentSlide'))+1;
    element.attr('data-currentSlide', value);
    slide(element);
}
function prev(element){
    let value = element.attr('data-currentSlide')-1;
    element.attr('data-currentSlide', value);
    slide(element);
}
function slide(element){
    let currentSlide = Number(element.attr('data-currentSlide'));
    let value = currentSlide * (-100);
    let container = element.find('.slides-container');
    let slideTotal = container.find('.slide').length;
    disableNav(element);

    //Boucle infinie
    if(currentSlide == slideTotal){
        let clone = container.find('.slide:first').clone();
        container.append(clone);

        //Ecouter la fin de la transition pour rembobiner le slider
        element.on('transitionend', function(){
            container.css('transition', 'none');
            container.css('left', 0);
            //retirer le clone + réinitialiser currentSlide attribute
            container.find('.slide:last-child').remove();
            element.attr('data-currentSlide', 0);
            // remettre la transition
            setTimeout(function(){
                container.css('transition', 'left 1s')},
                20);

        })
    }

    if (currentSlide == -1){
        var clone = container.find('.slide:last-child').clone();
        clone.css({
            'position' : 'absolute',
            'left' : '0',
            'top' : '0',
            'transform' : 'translateX(-100%)'
        });
        container.prepend(clone);

        container.on('transitionend', function(){
            $(this).off('transitionend');

            container.css('transition', 'none');
            container.css('left', (slideTotal -1) * -100 + '%');
            element.attr('data-currentSlide', slideTotal - 1);

            container.find('.slide:first-child').remove();
            setTimeout(function(){
                container.css('transition', 'left 1s');
            }, 20);
        })
    }

    container.on('transitionend', function(){
        $(this).off('transitionend');
        enableNav(element);
    })

    container.css('left', value+'%');
}

function disableNav(slider) {
    slider.find('nav button').attr('disabled', 'true');
}

function enableNav(slider){
    slider.find('nav button').removeAttr('disabled');
}*/