//alerts on signature pad
function erased(){
        swal('Signature erased!');

        function alert(words){
        var iframe1 = document.createElement("IFRAME");
        iframe1.setAttribute("src", 'data:text/plain,');
        document.documentElement.appendChild(iframe1);
        window.frames[0].window.alert('Signature erased!');
        iframe1.parentNode.removeChild(iframe1);
}
}
function providesig(){
        swal("Please provide a signature first.");
}
function sigsaved(){
        swal("Signature Saved!");
}

var wrapper = document.getElementById("signature-pad"),
    clearButton = wrapper.querySelector("[data-action=clear]"),
    saveButton = wrapper.querySelector("[data-action=save]"),
    canvas = wrapper.querySelector("canvas"),
    signaturePad;

// Adjust canvas coordinate space taking into account pixel ratio,
// to make it look crisp on mobile devices.
// This also causes canvas to be cleared.
function resizeCanvas() {
    // When zoomed out to less than 100%, for some very strange reason,
    // some browsers report devicePixelRatio as less than 1
    // and only part of the canvas is cleared then.
    var ratio =  Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext("2d").scale(ratio, ratio);
}

window.onresize = resizeCanvas;
resizeCanvas();

signaturePad = new SignaturePad(canvas);

var data = localStorage.getItem('signature');
		
if (data) {
  signaturePad.fromDataURL(data);
}




clearButton.addEventListener("click", function (event) {
    signaturePad.clear();
    localStorage.removeItem('signature');
    $('#sig').val(" ");
    erased();	

});

saveButton.addEventListener("click", function (event) {
    if (signaturePad.isEmpty()) {
        //alert("Please provide a signature first.");
        providesig();
        localStorage.clear();
    } else {
        signaturePad.toDataURL();
        sigsaved();

    }
});
 saveButton.addEventListener("click", function (event) {

     var dataURL = signaturePad.toDataURL("image/png");

     localStorage.setItem('signature', dataURL);
     $('#sig').val(dataURL);
     //localStorage.setItem('signature', dataURL.replace(/^data:image\/(png|jpg);base64,/, ""));
    });
