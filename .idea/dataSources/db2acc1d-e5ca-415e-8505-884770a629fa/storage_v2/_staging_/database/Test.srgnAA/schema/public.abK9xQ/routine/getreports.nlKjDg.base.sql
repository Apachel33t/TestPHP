create procedure getreports(userlogin character)
    language plpgsql
as
$$
BEGIN
    SELECT purpose, file_id, comment, datetime, file_name
    FROM
        users u
            inner join main m
                       on m.users_id = u.id
            inner join reports r
                       on m.reports_id = r.id
            inner join file f
                       on r.file_id = f.id
    WHERE u.login = userLogin;
END;
$$;

alter procedure getreports(char) owner to postgres;

