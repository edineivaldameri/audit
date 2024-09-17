CREATE function audit_context()
returns json AS
$function$
BEGIN
	BEGIN
		return current_setting('audit.context');
	EXCEPTION WHEN others THEN
		return json_build_object('user_id', 0, 'user_name', session_user);
	END;
END;
$function$
LANGUAGE plpgsql;
