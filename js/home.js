
window.addEvent('domready', function(){

	$$('a[href=#]', 'a[href=]').addEvent('click', $lambda(false)).removeProperty('href');

	//whatsNewRotator = new Cnc2.Rotator($('WhatsNewItems'), $$('#WhatsNewItems li'), $$('#WhatsNewControlUp', '#WhatsNewControlDown'), {loop: true, timer: 3000});
	hotViewRotator = new Cnc2.Rotator($('HotViewCategories'), $$('#HotViewCategories ul'), $$('#HotViewControlUp', '#HotViewControlDown'), {loop: true, timer: 5000, offset: {'y' : -4}});
	//mustHaveRotator = new Cnc2.Rotator($('MustHaveItems'), $$('#MustHaveItems li'), $$('#MustHaveControlLeft', '#MustHaveControlRight'), {loop:true, timer: 3200});

	//billboardTabber = new Cnc2.Tabber($$('#BillboardTabs li'), $$('#BillboardItems ol'), {timer: 4000, duration: 350});
	//$$('#BillboardTabs li')[$random(0, $$('#BillboardTabs li').length - 1)].fireEvent('click');
	//colotItemTabber = new Cnc2.Tabber($$('#ColorItemTabs li'), $$('#ColorItemCategories ul'), {duration: 350});

});