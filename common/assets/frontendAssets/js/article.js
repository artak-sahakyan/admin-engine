// Нравится - не нравится статья

(function() {
    // your page initialization code here
    // the DOM will be available here

    var myInit = {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
    };

    const article = document.querySelector('article');

    article.addEventListener('copy', (event) => {
        var body_element = document.getElementsByTagName('body')[0];
        var selection;
        selection = window.getSelection();
        var pagelink = '<br>Источник: &lt;a href="'+window.location.href+'" title="'+$('h1').html()+'"&gt;'+window.location.href+' &lt;/a&gt;';
        var copytext = selection + pagelink;
        var newdiv = document.createElement('div');
        newdiv.style.position='absolute';
        newdiv.style.left='-99999px';
        body_element.appendChild(newdiv);
        newdiv.innerHTML = copytext;
        selection.selectAllChildren(newdiv);
        window.setTimeout(function() {
            body_element.removeChild(newdiv);
        },0);
    });
    document.onkeyup = function (e) {
        if (e.ctrlKey && e.which == 13) {
            var id = '';
            if (!(text = window.getSelection().toString())) {
                alert('Выделите текст пожалуйста');
            }

            if (!isNaN(+window.location.pathname.split('-')[0].slice(1))) {
                id = +window.location.pathname.split('-')[0].slice(1);
            }

            if (text && id) {

                if(text.length > 170) {
                    alert('Пожалуйста выделите поконкретнее. Слишком большое выделение');
                    return;
                }

                var formData = new FormData();
                formData.append('id', id);
                formData.append('text', text);

                let myRequest = new Request('error-info', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        // 'Content-Type': 'application/json'
                    },
                    body: formData,
                    // body: JSON.stringify({'id': id, 'text': text}),
                });

                fetch(myRequest).then(function (response) {
                    if (response.status == 200) {
                        alert('Спасибо. Сообщение об ошибке отправлено.');
                    }
                });
            }
        }
    };

})();


