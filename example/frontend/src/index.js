import "./index.css";

import _ from "lodash";
import $ from "jquery";

$(() => {
  const $element = $("<div></div>");

  $element.text(_.join(["Hello", "from", "index.js"], " "));
  $element
    .hide()
    .appendTo($(".content"))
    .fadeIn("slow");

  //   $("body").append($element);
});
