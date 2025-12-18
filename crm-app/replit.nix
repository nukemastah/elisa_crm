{ pkgs }: {
  deps = [
    pkgs.php82
    pkgs.php82Packages.composer
    pkgs.nodejs_18
    pkgs.postgresql
  ];
}
