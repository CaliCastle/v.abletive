var Template = function () {
    return `<?xml version="1.0" encoding="UTF-8" ?>
    <document>
       <menuBarTemplate>
          <menuBar>
             <menuItem template="${this.BASEURL}templates/Index.xml" presentation="menuBarItemPresenter">
                <title>特色课程</title>
             </menuItem>
             <menuItem template="${this.BASEURL}templates/Search.xml.js" presentation="menuBarItemPresenter" search="true">
                <title>搜索</title>
             </menuItem>
             <menuItem template="${this.BASEURL}templates/My.xml.js" presentation="menuBarItemPresenter" load="true">
                <title>我的课程</title>
             </menuItem>
          </menuBar>
       </menuBarTemplate>
    </document>`;
}