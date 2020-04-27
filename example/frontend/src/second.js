import $ from "jquery";

$(() => {
  const $element = $("<div></div>");

  $element.text(_.join(["Hello", "from", "second.js"], " "));
  $element.hide().appendTo($(".content")).fadeIn("slow");
});
