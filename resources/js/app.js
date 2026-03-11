require("./bootstrap");

import toastr from "toastr";
import "toastr/build/toastr.min.css";

window.toastr = toastr;

toastr.options = {
    positionClass: "toast-top-right",
    timeOut: 3000,
    progressBar: true,
    closeButton: true,
};

import $ from "jquery";
window.$ = window.jQuery = $;
