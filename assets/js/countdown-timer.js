$(function(){

    // Check for elements that need a countdown timer, initiate
    var _second = 1000;
    var _minute = _second * 60;
    var _hour = _minute * 60;
    var _day = _hour * 24;
    var _week = _day * 7;
    var timer;

    $(".countdown").each(function(){

        var element = $(this);
        var end = new Date(element.attr('id'));
        
        function showRemaining() {
            var now = new Date();
            var distance = end - now;
            if (distance < 0) {

                clearInterval(timer);
                element.html('EXPIRED!');

                return;
            }
            var weeks = Math.floor(distance / _week);
            var days = Math.floor(distance / _day) - (weeks * 7);
            var hours = ('0' + Math.floor((distance % _day) / _hour)).slice(-2);
            var minutes = ('0' + Math.floor((distance % _hour) / _minute)).slice(-2);
            var seconds = ('0' + Math.floor((distance % _minute) / _second)).slice(-2);

            element.html('');

            if(weeks !== 0){
                if(weeks == 1){
                    element.append(weeks + ' week ');
                } else {
                    element.append(weeks + ' weeks ');
                }
            }

            if(weeks !== 0){
                if(days == 1){
                    element.append(days + ' day ');
                } else {
                    element.append(days + ' days ');
                }
                
            }
            element.append(hours + ':');
            element.append(minutes + ':');
            element.append(seconds + '');
        }

        timer = setInterval(showRemaining, 1000);
        showRemaining();

    });

});