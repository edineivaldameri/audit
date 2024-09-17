CREATE function public.audit()
returns trigger AS
$function$
BEGIN
	if (audit_enabled() = false) THEN
		return null;
	END if;

	if (TG_OP = 'DELETE') THEN
		INSERT INTO audits ("event", "date", "schema", "table", "context", "before", "after")
		VALUES ('DELETE', now(), TG_TABLE_SCHEMA::text, TG_TABLE_NAME::text, audit_context(), to_json(old.*), null);

		return old;
	END if;

	if (TG_OP = 'UPDATE') THEN
		INSERT INTO audits ("event", "date", "schema", "table", "context", "before", "after")
		VALUES ('UPDATE', now(), TG_TABLE_SCHEMA::text, TG_TABLE_NAME::text, audit_context(), to_json(old.*), to_json(new.*));

		return old;
	END if;

	if (TG_OP = 'INSERT') THEN
		INSERT INTO audits ("event", "date", "schema", "table", "context", "before", "after")
		VALUES ('INSERT', now(), TG_TABLE_SCHEMA::text, TG_TABLE_NAME::text, audit_context(), null, to_json(new.*));

		return old;
	END if;

	return null;
END;
$function$
LANGUAGE plpgsql;
