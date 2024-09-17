CREATE function audit_enabled()
returns boolean AS
$function$
BEGIN
	BEGIN
		return current_setting('audit.enabled');
	EXCEPTION WHEN others THEN
		return true;
	END;
END;
$function$
LANGUAGE plpgsql;
