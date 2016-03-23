[{include file="headitem.tpl" title="OEGUESTBOOK_ADMINGB_TITLE"|oxmultilangassign}]

[{if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<form name="transfer" id="transfer" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="cl" value="oeguestbookadminguestbookmain">
</form>


<table cellspacing="0" cellpadding="0" border="0" style="width:100%;height:100%;">
  <form name="myedit" id="myedit" action="[{$oViewConf->getSelfLink()}]" method="post" onSubmit="copyLongDesc( 'oeguestbookentry__oxcontent' );">
  [{$oViewConf->getHiddenSid()}]
  <input type="hidden" name="cl" value="oeguestbookadminguestbookmain">
  <input type="hidden" name="fnc" value="">
  <input type="hidden" name="oxid" value="[{$oxid}]">
  <input type="hidden" name="voxid" value="[{$oxid}]">
  <input type="hidden" name="editval[oeguestbookentry__oxid]" value="[{$oxid}]">
  <tr>
    <td valign="top" class="edittext" width="250">
      <table cellspacing="0" cellpadding="0" border="0">
        [{block name="admin_adminguestbook_main_form"}]
            <tr>
              <td class="edittext">
              [{oxmultilang ident="GENERAL_DATE"}]&nbsp;
              </td>
              <td class="edittext">
              <input type="text" class="editinput" size="27" value="[{$edit->oeguestbookentry__oxcreate|oxformdate}]" [{include file="help.tpl" helpid=article_vonbis}] readonly [{$readonly}]>
              <input type="hidden" name="editval[oeguestbookentry__oxcreate]" value="[{$edit->oeguestbookentry__oxcreate->value}]">
              [{oxinputhelp ident="HELP_GENERAL_DATE"}]
              </td>
            </tr>
            [{if $blShowActBox}]
            <tr>
              <td class="edittext">
              [{oxmultilang ident="GENERAL_ACTIVE"}]&nbsp;
              </td>
              <td class="edittext">
              <input class="edittext" type="checkbox" name="editval[oeguestbookentry__oxactive]" value='1' [{if $edit->oeguestbookentry__oxactive->value == 1}]checked[{/if}] [{$readonly}]>
              [{oxinputhelp ident="HELP_GENERAL_ACTIVE"}]
              </td>
            </tr>
            [{/if}]
        [{/block}]
        <tr>
          <td class="edittext">
          </td>
          <td class="edittext"><br>
          <input type="submit" [{if $oxid=="-1"}]disabled[{/if}] class="edittext" name="save" value="[{oxmultilang ident="GENERAL_SAVE"}]" onClick="Javascript:document.myedit.fnc.value='save'"" [{$readonly}]>
          </td>
        </tr>
      </table>
    </td>

    <!-- Anfang rechte Seite -->
    <td valign="top" class="edittext vr" align="left">
        [{block name="admin_adminguestbook_main_content"}]
            <textarea class="editinput" cols="100" rows="17" wrap="VIRTUAL" name="editval[oeguestbookentry__oxcontent]" [{$readonly}]>[{$edit->oeguestbookentry__oxcontent->value}]</textarea>
        [{/block}]
    </td>
  </tr>
</table>

</form>
[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]
