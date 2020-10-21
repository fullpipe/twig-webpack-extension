import "./inline.css";
import $ from "jquery";

$(() => {
  const $element = $(`<div class="inline"></div>`);

  $element.text(_.join(["Hello", "from", "inline.js"], " "));
  $element.hide().appendTo($(".content")).fadeIn("slow");
});
