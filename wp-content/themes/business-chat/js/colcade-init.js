"use strict";

(function () {
  if (
    document.getElementsByClassName("business-chat-colcade-column").length <=
      0 ||
    document.getElementsByClassName("all-blog-articles").length <= 0 ||
    document.getElementsByClassName("blogposts-list").length <= 0
  ) {
    return;
  }

  var business_chat_colcade = new Colcade(
    ".add-blog-to-sidebar .all-blog-articles",
    {
      columns: ".business-chat-colcade-column",
      items: ".posts-entry.blogposts-list",
    }
  );
})();
