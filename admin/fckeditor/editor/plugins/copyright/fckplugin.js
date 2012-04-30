//copyright button by kdos
function CopyRight()
{
}

// Disable button toggling.
CopyRight.prototype.GetState = function() 
{
	return FCK_TRISTATE_OFF;
}

// Our method which is called on button click.
CopyRight.prototype.Execute = function()
{
  //alert("webtrigger ©2009");
   var EditorInstance = FCKeditorAPI.GetInstance('FCKeditor1');
   EditorInstance.InsertHtml('msleague.gr ©2010');
}

// Register the command.
FCKCommands.RegisterCommand('copyright', new CopyRight());

// Add the button.
var item = new FCKToolbarButton('copyright', 'insert copyright info');
item.IconPath = FCKPlugins.Items['copyright'].Path + 'copyright.gif';
FCKToolbarItems.RegisterItem('copyright', item);
