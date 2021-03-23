<?php

function login($user, $pass) {
	$login = select('*', "users", "username = '$user'");
	if (cekRow($login) > 0) {
		$user = mysqli_fetch_object($login);

		if (password_verify($pass, $user->password)) {
			setSessionUser($user);
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

function logout() {
	resetSessionUser();
	redirect(base_url("admin/login"));
}