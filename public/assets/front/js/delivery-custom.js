function countDownTime(){
var remSec = $(document).find("#count"),
        countSec = 0,
        timer = setInterval(() => {
        countSec >= 0 ? remSec.val(countSec++) : clearInterval(timer);
        }, 1000);
}





