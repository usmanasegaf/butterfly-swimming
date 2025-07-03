#!/usr/bin/env bash
# show_clean.sh – tampilkan isi file sumber, sembunyikan asset, komentar & berkas panjang

for f in "$@"; do
  case "$f" in
    # -----------------------------------------------------------------
    # 1) Asset biner / minified / font / gambar → tampilkan nama saja
    # -----------------------------------------------------------------
    *jquery*.js|*.min.js|*.min.css|*.bundle.js|*.map|\
    *.svg|*.png|*.jpg|*.jpeg|*.webp|*.gif|*.ico|\
    *.ttf|*.woff|*.woff2|*.eot)
      printf "\n\e[1;34m[SKIP ASSET] ==> %s\e[0m\n" "$f"
      ;;

    # -----------------------------------------------------------------
    # 2) Berkas lock & metadata panjang → nama saja
    # -----------------------------------------------------------------
    package-lock.json|yarn.lock|composer.lock|*.lock)
      printf "\n\e[1;34m[SKIP LOCK FILE] ==> %s\e[0m\n" "$f"
      ;;

    # -----------------------------------------------------------------
    # 3) README → nama saja
    # -----------------------------------------------------------------
    README.md|readme.md|README.MD)
      printf "\n\e[1;34m[SKIP README] ==> %s\e[0m\n" "$f"
      ;;

    # -----------------------------------------------------------------
    # 4) Blade template – tampilkan, tapi hilangkan <style>, <svg>, komentar
    # -----------------------------------------------------------------
    *.blade.php)
      printf "\n\e[1;34m===== %s =====\e[0m\n" "$f"
      awk '
        /<style/        {print "\033[1;31m[SKIPPED INLINE CSS BLOCK]\033[0m"; in_style=1; next}
        /<\/style>/     {in_style=0; next}
        /<svg/          {print "\033[1;31m[SKIPPED SVG BLOCK]\033[0m";  in_svg=1; next}
        /<\/svg>/       {in_svg=0; next}
        in_style || in_svg {next}

        /^\s*(\/\/|#)/          {next}                       # single‑line
        /^\s*\/\*/              {in_c=1; next}               # /* …
        in_c && /\*\//          {in_c=0; next}
        in_c                   {next}

        /^\s*<!--/             {in_h=1; next}               # <!-- …
        in_h && /-->/          {in_h=0; next}
        in_h                  {next}

        /^\s*\{\{--/           {in_b=1; next}               # {{-- …
        in_b && /--\}\}/       {in_b=0; next}
        in_b                  {next}

        {print}
      ' "$f"
      printf "\n\e[1;33m-----------------------------\e[0m\n"
      ;;

    # -----------------------------------------------------------------
    # 5) File teks lain – tampilkan tanpa komentar //, #, /* … */
    # -----------------------------------------------------------------
    *)
      printf "\n\e[1;34m===== %s =====\e[0m\n" "$f"
      awk '
        /^\s*(\/\/|#)/         {next}
        /^\s*\/\*/             {in_c=1; next}
        in_c && /\*\//         {in_c=0; next}
        in_c                  {next}
        {print}
      ' "$f"
      printf "\n\e[1;33m-----------------------------\e[0m\n"
      ;;
  esac
done
