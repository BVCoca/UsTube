const commentTitle = document.getElementById("commentTab");
const videoTitle = document.getElementById("videoTab");
const videoTab = document.getElementById("tabVideo");
const commentTab = document.getElementById("tabComment");

commentTitle.onclick = showComment;
videoTitle.onclick = showVideo;

function showComment() {
  commentTab.classList.add("activeTab");
  videoTab.classList.add("notActiveTab");
  commentTab.classList.remove("notActiveTab");
}

function showVideo() {
  console.log("test");
  commentTab.classList.add("notActiveTab");
  videoTab.classList.add("activeTab");
  videoTab.classList.remove("notActiveTab");
}
