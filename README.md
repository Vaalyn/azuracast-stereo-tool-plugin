Make the file at `/var/azuracast/plugins/StereoToolPlugin/scripts/11-stereo-tool-dir.sh` executable via the following command:

```bash
chmod +x /var/azuracast/plugins/StereoToolPlugin/scripts/11-stereo-tool-dir.sh
```

Create a file with the name `docker-compose.override.yml` in your `/var/azuracast` directory with the following content:

```
services :
  web :
    volumes :
      - ./plugins/StereoToolPlugin:/var/azuracast/plugins/StereoToolPlugin
      - ./plugins/StereoToolPlugin/scripts/11-stereo-tool-dir.sh:/etc/my_init.d/11-stereo-tool-dir.sh
      - ./stereo_tool:/var/azuracast/servers/stereo_tool

  stations :
    volumes :
      - ./stereo_tool:/var/azuracast/servers/stereo_tool
```
