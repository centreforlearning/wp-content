(function($){
  $.fn.rowGrid = function( options ) {
    return this.each(function() {
      $this = $(this);
      if(options === 'appended') {
        options = $this.data('grid-options');
        var $lastRow = $this.children('.' + options.lastRowClass);
        var items = $lastRow.nextAll(options.itemSelector).add($lastRow);
        layout(this, options, items);
      } else {
        options = $.extend( {}, $.fn.rowGrid.defaults, options );
        $this.data('grid-options', options);
        layout(this, options);
        
        if(options.resize) {
          $(window).on('resize.rowGrid', {container: this}, function(event) {
            // $('.debug').append('<p>Resize-triggered</p>');
            layout(event.data.container, options);
          });
        }
      }
    });
  };
  
  $.fn.rowGrid.defaults = {
    minMargin: null,
    maxMargin: null,
    resize: true,
    lastRowClass: 'last-row',
    firstItemClass: null
  };
 
  function layout(container, options, items) {
    var rowWidth = 0,
    	itemHeight = 0,
        rowElems = [],
        items = jQuery.makeArray(items || container.querySelectorAll(options.itemSelector)),
        itemsSize = items.length,
        lastRow = false;
    // read
    var containerWidth = Math.floor(container.clientWidth-parseFloat($(container).css('padding-left'))-parseFloat($(container).css('padding-right')))-1;
    var itemAttrs = [];
    var theImage, w, h;
    // var $debug = $('.debug');
    for(var i = 0; i < itemsSize; ++i) {
      theImage = items[i].getElementsByTagName('img')[0];
      if (!theImage) {
        items.splice(i, 1);
        --i;
        --itemsSize;
        continue;
      }
      // get width and height via attribute or js value
      /*
      if (!(w = parseInt(theImage.getAttribute('width')))) {
        theImage.setAttribute('width', w = theImage.offsetWidth);
      } 
      if (!(h = parseInt(theImage.getAttribute('height')))) {
        theImage.setAttribute('height', h = theImage.offsetHeight);
      } */
      
      w = parseInt(theImage.getAttribute('width'));
      h = parseInt(theImage.getAttribute('height'));
      
      itemAttrs[i] = {
        width: w,
        height: h
      };
    }
    itemsSize = items.length;
    itemHeight = itemAttrs[0].height; // Assuming everything starts at the same height
	
	
	// $debug.append('<p>cWidth:'+containerWidth+'</p>');
	
    // write
    for(var index = 0; index < itemsSize; ++index) {
      if (items[index].classList) {
        items[index].classList.remove(options.firstItemClass);
        items[index].classList.remove(options.lastRowClass);
      } else {
        // IE <10
        items[index].className = items[index].className.replace(new RegExp('(^|\\b)' + options.firstItemClass + '|' + options.lastRowClass + '(\\b|$)', 'gi'), ' ');
      }

      rowWidth += itemAttrs[index].width;
      rowElems.push(items[index]);
      
      // check if it is the last element
      if(index === itemsSize - 1) {
        for(var rowElemIndex = 0; rowElemIndex<rowElems.length; rowElemIndex++) {
          // if first element in row
          if(rowElemIndex === 0) {
            rowElems[rowElemIndex].className += ' ' + options.lastRowClass;
          }
          lastRow = true;
        }
      }      
      
      // check whether width of row is too high
      if(rowWidth + options.maxMargin * (rowElems.length - 1) > containerWidth || lastRow) {
        var diff = rowWidth + options.maxMargin * (rowElems.length - 1) - containerWidth;
        var nrOfElems = rowElems.length;
        var nrOfMargins = nrOfElems - 1;
        // change margin
        var maxSave = (options.maxMargin - options.minMargin) * (nrOfMargins);
        if(diff > maxSave) {
          var rowScale = containerWidth / (rowWidth + options.minMargin * nrOfMargins);
		  var rowScaledWidth = 0;
		  for(var rowElemIndex = 0; rowElemIndex<rowElems.length; rowElemIndex++) {
			  rowScaledWidth += Math.floor(itemAttrs[index+rowElemIndex-rowElems.length+1].width * rowScale)
		  }
        } else {
          var rowScale = 1;
          var rowScaledWidth = rowWidth;
        }
        
        var rowHeight = Math.floor(rowScale * itemHeight);
        var rowMargin = Math.floor((containerWidth - rowScaledWidth)/nrOfMargins);
        if (rowMargin > options.maxMargin) rowMargin = options.minMargin;
        var extraPixels = (containerWidth - rowScaledWidth) % nrOfMargins;
        
        var rowElem;
        for(var rowElemIndex = 0; rowElemIndex<rowElems.length; rowElemIndex++) {
          rowElem = rowElems[rowElemIndex];
          var newWidth = Math.floor(itemAttrs[index+rowElemIndex-rowElems.length+1].width * rowScale);
          var itemMargin = rowMargin;
          if (rowElemIndex < extraPixels) itemMargin += 1;
          rowElem.style.cssText =
              'width: ' + newWidth + 'px;' +
              'height: ' + rowHeight + 'px;' +
              'margin-right: ' + ((rowElemIndex < rowElems.length - 1)?itemMargin : 0) + 'px';
          if(rowElemIndex === 0) {
            rowElem.className += ' ' + options.firstItemClass;
          }
        }
        
        // var message = '<p>Index:'+index+' srWidth: '+rowScaledWidth+' tMargins:'+rowMargin+' * '+nrOfMargins+' + '+extraPixels+' Scale:'+rowScale+' Height'+rowHeight+'px</p>';
      	// $debug.append(message);
        rowElems = [],
          rowWidth = 0;
      }
    }
  }
})(jQuery);