DomFrag - HTML Fragment Parser
==============================

Парсер HTML-фрагментов с поддержкой CSS-селекторов (как в jQuery)

СОЗДАНИЕ DOM
~~~~~~~~~~~~
$dom = DomFrag::NewFromFile('template.tpl');
$dom = DomFrag::NewFromString('<div class="test"><ul id="menu"><li>item 1</li><li>item 2</li>');

ПОИСК ЭЛЕМЕНТОВ
~~~~~~~~~~~~~~~
$dom->find('div.test ul');
$dom->find('#menu li');

МАНИПУЛИЯЦИИ
~~~~~~~~~~~~
$set = $dom->find('ul');

$set->prepend('<li>first item</li>');
$set->append('<li>last item</li>');
$set->before('<div>Header</div>');
$set->after('<div>Footer</div>');

ЦЕПОЧКИ ВЫЗОВОВ
~~~~~~~~~~~~~~~
$set = $dom->find('ul li')->append('text');

МЕТОДЫ
~~~~~~
find(<css_selector>);
prepend(<html>);
append(<html>);
before(<html>);
after(<html>);
replaceWith(<html>);
nodes();
first()
last()
html()
text()
