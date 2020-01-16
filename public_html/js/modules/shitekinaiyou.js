(function() {
  'use strict';

if(document.getElementsByClassName('hline')){
    var hline = document.getElementsByClassName('hline');
    for(var i = 0;i < hline.length;i++){
      if(hline[i].textContent.includes('最重要項目')){
        hline[i].style.color = 'red';
        hline[i].style.background = 'yellow';
      }
    }
}
if(document.getElementsByClassName('status')){
    var status = document.getElementsByClassName('status');
    for(var i = 0;i < status.length;i++){
      // if(status[i].textContent.includes('期限切れ')){
      //   status[i].style.color = 'red';
      //   status[i].style.background = '#ffc0cb';
      // }
      switch(status[i].textContent){
        case '期限切れ':
          status[i].style.color = 'red';
          status[i].style.background = '#ffc0cb';
        break;
        case '要撤去':
          status[i].style.background = '#ffc0cb';
        break;
        case '驚安期間':
          status[i].style.background = '#ffd700';
        break;
        case '値引期間':
          status[i].style.color = 'blue';
          status[i].style.background = '#e0ffff';
        break;
      }
    }
}

// if(document.getElementsByClassName('img3')){
//     var img3 = document.getElementsByClassName('img3');
//     var comment = document.getElementsByClassName('comment');
//     for(var i = 1;i < img3.length;i++){
//       var j = i - 1;
//       if(!img3[i].innerHTML.includes('.jpg') || !img3[i].innerHTML.includes('.png') || !img3[i].innerHTML.includes('.gif')){
//         var mikaizen = document.createElement('i');
//         mikaizen.classList.add('fas fa-thumbtack');
//         comment[j].appendChild(mikaizen);
//       }
//     }
// }


})();
