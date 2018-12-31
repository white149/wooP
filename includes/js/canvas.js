
var canvasid;
var previewData;


jQuery('.wpa-canvas-up-button').click(function() {
    jQuery('.wpa-canvas-1').show().attr('active', '1');
	jQuery('.wpa-canvas-2').hide().attr('active', '0');
	
});

jQuery('.wpa-canvas-down-button').click(function() {
	jQuery('.wpa-canvas-1').hide().attr('active', '0');
	jQuery('.wpa-canvas-2').show().attr('active', '1');
});

// jQuery(document).on( 'click', 'button[name=add-to-cart]', function() {
//     savePreview();
// });



jQuery(document).ready(function($){
    
});

jQuery(document).on( 'click', '.wpa-photo-img', function() {
    canvasid = jQuery('[active=1]').attr('id');
    canvas_width = document.getElementById('wpa_preview_01').clientWidth;
    canvas_height = document.getElementById('wpa_preview_01').clientHeight;
    preview_height = jQuery('.wpa-canvas').height();
    preview_width = jQuery('.wpa-canvas').width();
    
    
    var uploadRatio;
    if (this.clientHeight == this.clientWidth){
        uploadRatio = 1;
    } else if (this.clientHeight > this.clientWidth){
        uploadRatio = this.clientWidth / this.clientHeight;
       
    } else {
        uploadRatio = this.clientHeight / this.clientWidth;
    }

    var canvasRatio;
    if (canvas_height == canvas_width){
        canvasRatio = 1;
    }else if (canvas_height > canvas_width){
        canvasRatio = canvas_width / canvas_height;
    }else {
        canvasRatio = canvas_height / canvas_width;
    }
    if(canvas_width > canvas_height){ 
        if(this.clientWidth > this.clientHeight && uploadRatio > canvasRatio){
            preview_height = canvas_height;
            preview_width = preview_height / uploadRatio;
        }else if (this.clientWidth > this.clientHeight && uploadRatio < canvasRatio){
            preview_width = canvas_width;
            preview_height = preview_height * uploadRatio;
        } else if (this.clientWidth < this.clientHeight){
            preview_height = canvas_height;
            preview_width = canvas_height * uploadRatio;
        } else {
            preview_height = canvas_height;
            preview_width = preview_height;
        }
    } else if (canvas_width < canvas_height){
        if(this.clientWidth < this.clientHeight && uploadRatio < canvasRatio){
            preview_height = canvas_height;
            preview_width = preview_height * uploadRatio;
        }else if (this.clientWidth < this.clientHeight && uploadRatio > canvasRatio){
            preview_width = canvas_width;
            preview_height = preview_width / uploadRatio;
        } else if (this.clientWidth > this.clientHeight){
            preview_width = canvas_width;
            preview_height = canvas_width * uploadRatio;
        } else {
            preview_height = canvas_width;
            preview_width = preview_height;
        }
    }else {
        if (this.clientHeight > this.clientWidth){
            preview_height = canvas_height;
            preview_width = preview_height * uploadRatio;
        } else if (this.clientHeight < this.clientWidth){
            preview_width = preview_width;
            preview_height = preview_width * uploadRatio;
        }
    }

    jQuery('.'+canvasid + '_id').attr('value', jQuery(this).parent().attr('fileid'));
    if (canvasid == 'wpa_preview_01'){
        var layer1 = new Konva.Layer();
        var imageObj1 = new Image();
        imageObj1.src = jQuery(this).attr('src');
        var image1 = new Konva.Image({
            width: preview_width,
            height: preview_height,
            image: imageObj1,
            stroke:'red',
            strokeWidth: '8',
            dash: [10, 5],
        });
        

        var imgGroup1 = new Konva.Group({
            x: 0,
            y: 0,

            draggable: true
        });
        var stage1 = new Konva.Stage({
            container: canvasid,
            width: canvas_width,
            height: canvas_height
        });
        imgGroup1.add(image1);
        addAnchor(imgGroup1, -10, -10, 'topLeft');
        layer1.add(imgGroup1);
        stage1.add(layer1);
        // var transformer = new Konva.Transformer({
        //     node: imgGroup,
        //     centeredScaling: true,
        //     rotationSnaps: [0, 90, 180, 270],
        //     rotateEnabled: false,
        //     borderEnabled: false
        //     });
        // layer.add(transformer);
        image1.image();
        // stage.on('click', function(){
        //     deleteIcon.remove();
        // })
        
        previewData = stage1.toDataURL();
        //console.log(previewData);
        jQuery('.'+ canvasid +'_data').attr('value', previewData);
        
    } else {
        var layer2 = new Konva.Layer();
        var imageObj2 = new Image();
        
        var image2 = new Konva.Image({
            width: 200,
            height: 200
        });
        imageObj2.onload = function() {
            image2.image(imageObj2);
            layer2.draw();
        }
        var imgGroup2 = new Konva.Group({
            x: 0,
            y: 0,
            draggable: true
        });
        //jQuery('[active=1]').css('background-color', 'red');
        
    
        var stage2 = new Konva.Stage({
            container: canvasid,
            width: 700,
            height: 700
        });
        
        layer2.add(imgGroup2);
        stage2.add(layer2);
        addAnchor(imgGroup2, -10, -10, 'topLeft');
        imgGroup2.add(image2);
    
        // var transformer = new Konva.Transformer({
        //     node: imgGroup,
        //     centeredScaling: true,
        //     rotationSnaps: [0, 90, 180, 270],
        //     rotateEnabled: false,
        //     borderEnabled: false
        //     });
        // layer.add(transformer);
        
        imageObj2.src = jQuery(this).attr('src');
        previewData = stage2.toDataURL();
        savePreview();
    }
    
})
function savePreview(){
    jQuery('.'+ canvasid +'_data').attr('value', previewData);
}

function update(activeAnchor) {
    var group = activeAnchor.getParent();

    var topLeft = group.get('.topLeft')[0];
    var topRight = group.get('.topRight')[0];
    var bottomRight = group.get('.bottomRight')[0];
    var bottomLeft = group.get('.bottomLeft')[0];
    var image = group.get('Image')[0];

    var anchorX = activeAnchor.getX();
    var anchorY = activeAnchor.getY();

    // update anchor positions
    switch (activeAnchor.getName()) {
        case 'topLeft':
            topRight.setY(anchorY);
            bottomLeft.setX(anchorX);
            break;
        case 'topRight':
            topLeft.setY(anchorY);
            bottomRight.setX(anchorX);
            break;
        case 'bottomRight':
            bottomLeft.setY(anchorY);
            topRight.setX(anchorX);
            break;
        case 'bottomLeft':
            bottomRight.setY(anchorY);
            topLeft.setX(anchorX);
            break;
    }

    image.position(topLeft.position());

    var width = topRight.getX() - topLeft.getX();
    var height = bottomLeft.getY() - topLeft.getY();
    if(width && height) {
        image.width(width);
        image.height(height);
    }
}


var deleteIcon = new Image();
deleteIcon.src = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAADTUlEQVR42u2YbWiNYRjHzxk2L50oqZX3vAz7MofwYcU0L828EzbZUMpbPkyUUBZ1Ir744KVQXpLXmdJEQ2zFpPEF5WUKw+xQwzZix/+u/6mr43nucz/Pc875oF31+3Rf13X/z3Xu576v+/b5/lNLB7lgGzgN7oJHoB7UgLNgJ5gKuqdSWDY4DMIgYsg3cAKMT6awQeAi6BAT/wL3wEGwEawCpWADOABug7YYsddAVqLFrQE/xCR1FBIwiO0BloE7Iv4n2Ar8XoV1BcdE4hegwEO+PPBE5KvgD3At7rJIdiRBi13lDYmloj6unm4SnWQClWhTEtZ0EdewmqMSdHESvFZUbl0SP7xiUckdpkFDQSuDDqVg2yrnXL9BjklAJQOep2iDVX/tfc5ZG885R5R8ho2Pn1/yAAci+oLZmnUWFPPm6xIdp1ONxqeQPo1glIG4geAlY0o0fhX0uarbUFvoVBxnwkZDkVLc1zi+08Xp1M/KIZ8ObQb7UpYQ+cFmYiXulRA3Lk7ONNBE/6VWDrs4eMtwXSmR74XI0THndlTcFwNxUTun2z3Oc3C/g8VvJdKtOB/P5wgbkH/sAQfXO9wmRsaIfC3EBR3mWsjYBqvBpxwscrGXKZHvxOkTdiFO2TTGN1sNPvMgcLConOIjGONBYNhqsM7l2SvFhUUl3YhcoPuLL3Fwr0NxDULcWDBCiPzkUOQW3ZG3m4M3DZMNEeKaKS5qSuRbITLbMOcZxhy1GpzJQdXaZ3gQ51Zkmtj8l1s59ALf6bBYkygTvBHidC3ScCGyia2cnU0RbVemndMpOlVrEs2lz2fD/m2YELna4BSp0iWbILaKyZr+bQmrY2r92YCka+7afzjvrHjJquiobl/dUtCw+sWV9KHJVVR1Ju0M2JcCgWWcS1VwomnQZnGjK0miuDn8KCK8ijoq+wXxVZUmQVyhuJxV877s+PXqhqhkKEFrUu1320Xl1Lrr7TZZhqhkhE9ruR7EBXmMRcSpFUjEV1bGBx+ZeJ7BiRPdlgp4GeoQy6bc6WuCydd9PeYprYXPaXvASrCIHckKCrjCxlXG1NocjQmzSXxRbXXwgNnOh6g8XwotwC8xxErV8y6ieMzKqn10Pujj67ROs7e/vsM3cBlSPJIAAAAASUVORK5CYII=';
function addAnchor(group, x, y, name) {
    //var stage = group.getStage();
    var layer = group.getLayer();
    
    var anchor = new Konva.Image({
        x: x,
        y: y,
        width:20,
        height: 20,
        image: deleteIcon,
        //fill: '#f75555',
        //strokeWidth: 2,
        name: name,
        //draggable: true,
        //dragOnTop: false
    });

    // anchor.on('click', function() {
    //     group.remove();
    //     layer.draw();
    //     savePreview();
    // });
    // // anchor.on('mousedown touchstart', function() {
    // //     group.setDraggable(false);
    // //     this.moveToTop();
    // // });
    // // anchor.on('dragend', function() {
    // //     group.setDraggable(true);
    // //     layer.draw();
    // // });
    // // // add hover styling
    // anchor.on('mouseover', function() {
    //     //var layer = this.getLayer();
    //     document.body.style.cursor = 'pointer';
    //     // this.setStrokeWidth(4);
    //     // layer.draw();
    // });
    // anchor.on('mouseout', function() {
    //     //var layer = this.getLayer();
    //     document.body.style.cursor = 'default';
    //     //this.setStrokeWidth(2);
    //     //layer.draw();
    // });

    group.add(anchor);
    //console.log(group);
}

