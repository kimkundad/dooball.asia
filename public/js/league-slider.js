var imgOne = $('#mock_one').val();
var imgTwo = $('#mock_two').val();
var imgThree = $('#mock_three').val();
var imgFour = $('#mock_four').val();
var imgFive = $('#mock_five').val();

var slideLeft;
var touchSlide;
var newListing = [
                    { title: "How to buy flower with Cash?", slug: '/premier', img: imgOne },
                    { title: 'What is changing and why?', slug: '/premier', img: imgTwo },
                    { title: 'Protect Yourself from Scams', slug: '/premier', img: imgThree },
                    { title: 'How to Protect Your Pooldax Account from Phishing Scams', slug: '/premier', img: imgFour },
                    { title: 'How to verify an account?', slug: '/premier', img: imgFive }
                ];
                
function clearSlide() {
    slideLeft = 0;
    touchSlide = 0;
    var slideEle = '';

    if (newListing.length > 0) {
        for (var i = 0; i < newListing.length; i++) {
            slideEle += '<div class="slide-box">';
            // slideEle +=     '<a  class="slide-ele" href="' + newListing[i].slug + '">';
            slideEle +=         '<img src="' + newListing[i].img + '">';
            // slideEle +=         '<span>' + newListing[i].title + '</span>';
            // slideEle +=     '</a>';
            slideEle += '</div>';
        }
    }

    $('.slide-cover').html(slideEle);
    $('.slide-cover').css('transform', 'translate(0px, 0px)');
}

function prevSlide() {
    if (slideLeft < 0) {
        slideLeft += 130; // 115 + 15
        touchSlide--;
        $('.slide-cover').css('transform', 'translate(' + slideLeft + 'px, 0px)');
    }
}

function nextSlide() {
    slideLeft -= 130; // 115 + 15
    touchSlide++;
    var i = (touchSlide - 1);
    newListing.push(newListing[i]);
    var slideEle = '';
    
    slideEle += '<div class="slide-box">';
    // slideEle +=     '<a  class="slide-ele" href="' + newListing[i].slug + '">';
    slideEle +=         '<img src="' + newListing[i].img + '">';
    // slideEle +=         '<span>' + newListing[i].title + '</span>';
    // slideEle +=     '</a>';
    slideEle += '</div>';

    $('.slide-cover').append(slideEle);

    var wd = $('.slide-cover').css('width');
    var slideWidth = wd.replace('px', '');
    var wdth = parseInt(slideWidth, 10);
    var newWidth = wdth + 130; // 115 + 15

    $('.slide-cover').css('width', newWidth + 'px');
    $('.slide-cover').css('transform', 'translate(' + slideLeft + 'px, 0px)');
}