function Array2d(dimensions) {
    this.dimensions = dimensions;
    this.update = function() {
        this.dimensions.forEach(function(dim) {
            var name = dim.name;
            var attributes = dim.attributes;
            attributes.forEach(function(attr) {
                $('.array-2d')
                    .filter('[data-dim-1="' + name + '"]')
                    .filter('[data-attr="' + attr + '"]')
                    .each(function(index, item) {
                    $(item).attr('name', name + '[' + index + '][' + attr + ']');
                })
            });
        })
    }
}