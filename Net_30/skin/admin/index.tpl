<form action="{$VAL_SELF}" method="post" enctype="multipart/form-data">
	<div id="Net_30" class="tab_content">
  		<h3>{$TITLE}</h3>
  		<p>{$LANG.net_30.module_description}</p>
  		<fieldset><legend>{$LANG.module.cubecart_settings}</legend>
			<div><label for="status">{$LANG.common.status}</label><span><input type="hidden" name="module[status]" id="status" class="toggle" value="{$MODULE.status}" /></span></div>
			<div><label for="position">{$LANG.module.position}</label><span><input type="text" name="module[position]" id="position" class="textbox number" value="{$MODULE.position}" /></span></div>
			<div><label for="default">{$LANG.common.default}</label><span><input type="hidden" name="module[default]" id="default" class="toggle" value="{$MODULE.default}" /></span></div>
			<div><label for="description">{$LANG.common.description} *</label><span><input name="module[desc]" id="description" class="textbox" type="text" value="{$MODULE.desc}" /></span></div>
		</fieldset>
		<p>{$LANG.module.description_options} {$LANG.net_30.gateway_description_setting_addendum}</p>
		<h3>{$LANG.net_30.enabled_groups}</h3>
		<fieldset id="enabled-groups"><legend>{$LANG.net_30.title_groups_enabled}</legend>
		{if isset($ENABLED_GROUPS)}
		{foreach from=$ENABLED_GROUPS item=group}
			<div>
				<span class="actions"><a href="#" class="remove dynamic" title="{$LANG.messages.confirm_delete}"><i class="fa fa-trash" title="{$LANG.common.delete}"></i></a></span>
				<input type="hidden" name="module[groups][]" value="{$group.group_id}">
				{$group.group_name}
			</div>
		{/foreach}
		{/if}
		</fieldset>
		{if isset($CUSTOMER_GROUPS)}
		<fieldset><legend>{$LANG.net_30.title_groups_add}</legend>
		<div class="inline-add">
			<label for="add-group">{$LANG.net_30.groups_add}</label>
			<span>
				<select id="add-group" name="module[groups][]" class="textbox add display">
					<option value="">{$LANG.form.please_select}</option>
					{foreach from=$CUSTOMER_GROUPS item=group}
						<option value="{$group.group_id}">{$group.group_name}</option>
					{/foreach}
				</select>
				<a href="#" class="add" target="enabled-groups"><i class="fa fa-plus-circle" title="{$LANG.common.add}"></i></a>
			</span>
		</div>
		</fieldset>
		{/if}
	</div>
	{$MODULE_ZONES}
	<div class="form_control">
		<input type="submit" name="save" value="{$LANG.common.save}" />
	</div>
  	<input type="hidden" name="token" value="{$SESSION_TOKEN}" />
</form>