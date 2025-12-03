<#
PowerShell script to generate poster variants using ImageMagick (magick).
Usage examples (PowerShell):

# Keep aspect ratio and generate 1600/1024/640 WebP
.
# .\scripts\resize-posters.ps1 -Mode keep

# Crop to 16:9 center, then generate variants
# .\scripts\resize-posters.ps1 -Mode crop16by9

# Requirements: ImageMagick installed and `magick` in PATH.
##>

param(
    [ValidateSet('keep','crop16by9')]
    [string]$Mode = 'keep'
)

$srcDir = Join-Path $PSScriptRoot '..\public\images\posters' | Resolve-Path
$dstDir = Join-Path $PSScriptRoot '..\public\images\posters\generated' | Resolve-Path -ErrorAction SilentlyContinue
if (-not $dstDir) { New-Item -ItemType Directory -Path (Join-Path $PSScriptRoot '..\public\images\posters\generated') | Out-Null }
$dstDir = Join-Path $PSScriptRoot '..\public\images\posters\generated'

Write-Host "Mode: $Mode"
Get-ChildItem -Path $srcDir -Include *.jpg,*.jpeg,*.png,*.webp -File | ForEach-Object {
    $in = $_.FullName
    $base = [IO.Path]::GetFileNameWithoutExtension($_.Name)
    Write-Host "Processing: $($_.Name) => $base"

    if ($Mode -eq 'crop16by9') {
        # crop to 16:9 by trimming center, then resize
        magick "$in" -gravity center -resize 3200x3200^ -extent 3200x1800 -quality 85 "$dstDir\$base-1600.webp"
        magick "$in" -gravity center -resize 2048x2048^ -extent 2048x1152 -quality 85 "$dstDir\$base-1024.webp"
        magick "$in" -gravity center -resize 1280x1280^ -extent 1280x720 -quality 85 "$dstDir\$base-640.webp"
    } else {
        # keep aspect ratio, resize by width
        magick "$in" -resize 1600x -quality 85 "$dstDir\$base-1600.webp"
        magick "$in" -resize 1024x -quality 85 "$dstDir\$base-1024.webp"
        magick "$in" -resize 640x -quality 85 "$dstDir\$base-640.webp"
    }
}

Write-Host "Done. Generated images are in public/images/posters/generated/"
