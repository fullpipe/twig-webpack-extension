import $ from "jquery";

$(() => {
  const $element = $(`<div class="second"></div>`);

  $element.text(_.join(["Hello", "from", "second.js"], " "));
  $element.hide().appendTo($(".content")).fadeIn("slow");
});
