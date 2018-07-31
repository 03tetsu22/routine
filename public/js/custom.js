$(function(){
    // $('.clicktable').foreEach
    $('.area').on('click',function(){
        // クリックしたやつのクラスなりidを取得してそれを#form1のところと入れ替えてsubmitする
        var num = $(this).attr('id');
        num = '#form' + num;
        $(num).submit();
    })
    $('.delete').submit(function(){
        return confirm('本当に削除しますか？');
    });
    $('.login-name').click(function(event){
        event.preventDefault();
        event.stopPropagation();
        var $this = $(this); //$this = .dropdown-menu > a
        if($this.hasClass('open')){
            $this.removeClass('open').next().hide(); // .dropdown-menu > aの次の兄弟要素(itemsクラス)を隠す
            $('html').off('click',closeItems); // htmlをクリックした時closeItems関数が発動を解除
        }else{
            $this.addClass('open').next().show();
            $('html').on('click',closeItems);
        }
        function closeItems(){
            $this.removeClass('open')
            .next().hide();
        }
    });
    $('[data-toggle=tooltip-tool]').tooltip();
    $("[data-toggle=popover]").popover({
        trigger: 'hover',
    });
    $('.first').siblings().addClass('first-font');
    if($('.pt-month').hasClass('out')){
        $('.month').addClass('danger');
    }else if($('.pt-month').hasClass('clear')){
        $('.month').addClass('safe');
    };
    if($('.pt-year').hasClass('out')){
        $('.year').addClass('danger');
    }else if($('.pt-year').hasClass('clear')){
        $('.year').addClass('safe');
    };
    $('.select_submit').change(function(){
        $('.select_form').submit();
    });
});
