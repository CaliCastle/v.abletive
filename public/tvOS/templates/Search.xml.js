var Template = function() {
    return `<?xml version="1.0" encoding="UTF-8" ?>
  <document>
    <head>
      <style>
        .suggestionListLayout {
          margin: -150 0;
        }
        .heading {
          padding 25;
          font-size: 45pt;
          font-weight: medium;
          text-align: center;
          color: rgba(22,5,5,0.67);
        }
        .cornered {
          tv-img-treatment: corner-large;
        }
        .showOnHighlight {
          tv-text-highlight-style: show-on-highlight;
          font-size: 20pt;
        }
        .series-list {
          margin: 20;
        }
        .lesson-list {
          margin: 45;
        }
      </style>
    </head>
    <searchTemplate>
      <searchField>Search</searchField>
      <collectionList>
         <title class="heading">搜索你感兴趣的内容</title>
      </collectionList>
    </searchTemplate>
  </document>`;
}