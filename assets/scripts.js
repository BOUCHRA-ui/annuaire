$(function () {


    if ($('.confirm').length > 0) {

        // Confirmation de suppression
        $('.confirm').on('click', function () {
            return (confirm('Etes vous sûr(e) de vouloir supprimer ce contact ?'));
        })

    }

});

// EN JS PUR
// document.addEventListener('DOMContentLoaded',function(){

//     let collection = document.getElementsByClassName('confirm');
//     for( let i=0; i < collection.length ; i++){

//         collection[i].addEventListener('click',function(e){
//             e.preventDefault();
//             if(confirm('Etes vous sûr(e) de vouloir supprimer ce contact ?')){
//                 window.location = this.href;   
//             }
//         });

//     }

// });