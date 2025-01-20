const sortable = Sortable.create(miTablaMov, {
    animation: 150,
    handle: 'tr',
    onEnd: function (/**Event*/evt) {
        console.log('Elemento movido de la posición ' + evt.oldIndex + ' a ' + evt.newIndex);
    }
});