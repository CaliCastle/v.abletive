var Template = function () {
    return `<?xml version="1.0" encoding="UTF-8" ?>
    <document>
       <menuBarTemplate>
          <menuBar>
             <menuItem template="${this.BASEURL}templates/Index.xml" presentation="menuBarItemPresenter">
                <title>特色课程</title>
             </menuItem>
             <menuItem template="${this.BASEURL}templates/Skills.xml.js" presentation="menuBarItemPresenter">
                <title>技能方向</title>
             </menuItem>
             <menuItem template="${this.BASEURL}templates/Search.xml.js" presentation="menuBarItemPresenter">
                <title>搜索</title>
             </menuItem>
             <menuItem template="${this.BASEURL}templates/Account.xml.js" presentation="menuBarItemPresenter">
                <title>帐号</title>
             </menuItem>
          </menuBar>
       </menuBarTemplate>
    </document>`;
}