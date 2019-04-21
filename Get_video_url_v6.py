
# coding: utf-8

# In[20]:


#!/usr/bin/env python
# coding: utf-8

# # ID, accessToken, accountType
### fed485adb9474e4893bb70540501648c : mirae
# ### 6449d31711304f678a3912fadd72e54d : me

# In[21]:


import http.client, urllib.request, urllib.parse, urllib.error, base64
import yaml
import json
import sys
from io import BytesIO
from collections import OrderedDict
import os #####
from PIL import Image


# ocp_key = str("37b9a77defe74c07badbb2be73f1bdfd") #얘를 넣어서 나오는 유저아이디 ident 임 폴더 만들고 
ocp_key = sys.argv[1] 
#print("[Your ocpkey]"+ocp_key)

def get_id_and_access_token(ocp_key):  #sys.args
    headers = {
        # Request headers
        'Ocp-Apim-Subscription-Key': ocp_key,
    }

    params = urllib.parse.urlencode({
        # Request parameters
        'generateAccessTokens': 'True',
        'allowEdit': 'False',
    })

    try:
        conn = http.client.HTTPSConnection('api.videoindexer.ai')
        conn.request("GET", "/auth/trial/Accounts?%s" % params, "{body}", headers)
        #print("*****Now Getting Azure ID and Access Token*****")
        response = conn.getresponse()
        userIdAndAccessToken = response.read()
        conn.close()
        idTokenDict=yaml.load(userIdAndAccessToken)
        #print('[id] %s'%idTokenDict[0]['id'])
        #print('[access Token]%s'%idTokenDict[0]['accessToken'])
        #print('[account Type]%s'%idTokenDict[0]['accountType'])
        ident=idTokenDict[0]['id']
        accessToken=idTokenDict[0]['accessToken']
        accountType=idTokenDict[0]['accountType']
        
        return ident, accessToken, accountType
    except Exception as e:
        print("[Errno {0}] {1}".format(e.errno, e.strerror))


# In[23]:


ident, accessToken, accountType = get_id_and_access_token(ocp_key) # trial


# # Get Video Lists 

# In[24]:


# In[4]:


#####
laugh_path = '/home/ubuntu/www/laugh/'+ident

if not(os.path.isdir(ident)):
    os.makedirs(os.path.join(ident))
if not(os.path.isdir(laugh_path)):
    os.makedirs(laugh_path)


    #print("[!already exists folder]")
      
# In[5]:

def get_video_lists(ident,accessToken, accountType):
    headers = {
    }

    params = urllib.parse.urlencode({
        # Request parameters
        'pageSize': '25',
        'skip': '0',
    })

    try:
        conn = http.client.HTTPSConnection('api.videoindexer.ai')
        conn.request("GET", "/%s/Accounts/%s/Videos?accessToken=%s&%s" % (accountType,ident,accessToken,params), "{body}", headers)
        response = conn.getresponse()
        videoList = response.read()
        conn.close()
        videosDict=yaml.load(videoList)
        videoList=[]
        videoNameList=[]
        videoThumbnailNameList=[]
        for i, dictionary in enumerate(videosDict['results']):
            # print(dictionary['id'])
            videoList.append(dictionary['id'])
            videoNameList.append(dictionary['name'])
            videoThumbnailNameList.append(dictionary['thumbnailId'])
            # print(videoThumbnailNameList)
            

        return videoList,videoNameList,videoThumbnailNameList
    except Exception as e:
        print("[Errno {0}] {1}".format(e.errno, e.strerror))


# In[ ]:


videoList, videoNameList, videoThumbnailNameList = get_video_lists(ident,accessToken, accountType)


# In[15]:


# print(videoList)
# print(videoNameList)
# print(videoThumbnailNameList)


# In[16]:


videoList, videoNameList, videoThumbnailNameList = get_video_lists(ident,accessToken, accountType)


# In[21]:

# 
videoData = OrderedDict()
dictionary_video = dict(zip(videoList,videoNameList))
videoData['videoDict'] = dictionary_video
videoData['ident'] = ident
videoData['accessToken'] = accessToken

# print(videoList)
# print(videoNameList)

#print JSON and Save File
#print(json.dumps(videoData,ensure_ascii = False,indent = '\t'))


# In[24]:


with open('{}/videoData.json'.format(ident),'w',encoding='utf-8') as make_file:
    json.dump(videoData,make_file,ensure_ascii = False,indent = '\t')


# In[25]:

videoList, videoNameList, videoThumbnailNameList = get_video_lists(ident,accessToken, accountType)


videoId=videoList
videoThumbnailId=videoThumbnailNameList


# In[26]:


# print(videoId)
# print(videoThumbnailId) #첫번째 썸네일 아이디
# print(videoNameList)


# In[11]:

# print(videoId)
# print(videoThumbnailId) #첫번째 썸네일 아이디


# In[34]:


def get_thumbnail(ident,accessToken, accountType,videoId, videoThumbnailId):  # pass in thumbnail id, etc... and save them in ./userid/videos/ as thumbnail.jpg 
        
    headers = {
    }

    params = urllib.parse.urlencode({
        # Request parameters
        'format': 'Jpeg',
        'accessToken': accessToken,
    })

    try:
        for i in range(len(videoThumbnailId)):
            conn = http.client.HTTPSConnection('api.videoindexer.ai')
            videoId_2 =  videoId[i]
            videoThumbnailId_2 = videoThumbnailId[i]
            # print(videoThumbnailId_2)
            conn.request("GET", "/%s/Accounts/%s/Videos/%s/Thumbnails/%s?%s" % (accountType, ident, videoId_2, videoThumbnailId_2, params), "{body}", headers)
            response = conn.getresponse()
            thumbnail = response.read()
            # print(thumbnail)
            #####썸네일 이미지 저장
            img = Image.open(BytesIO(thumbnail))
            
            img.save('{}/{}.jpg'.format(ident,videoId_2))

    
#         print('ddd', thumbnail.shape)
        #print('[thumbnail read successfully. if you need thumbnail as file,  save it ]')
        conn.close()
        #return thumbnail
    except Exception as e:
        print("[Errno {0}] {1}".format(e.errno, e.strerror))
        
    return thumbnail


# In[12]:

get_thumbnail(ident, accessToken, accountType, videoId, videoThumbnailId)

# # Get Video URL

# In[38]:

def getVideoUrl(ident,accessToken,accountType,videoId,):   # make directory for each id  
    headers = {
    }

    params = urllib.parse.urlencode({
        # Request parameters
        'accessToken':accessToken,
    })

    try:
#         for i in range(len(videoThumbnailId)):
#             print(i)
            conn = http.client.HTTPSConnection('api.videoindexer.ai')
#             videoId_2 =  videoId[i]
#             videoId_list.append(videoId_2)
            conn.request("GET", "/%s/Accounts/%s/Videos/%s/SourceFile/DownloadUrl?%s" % (accountType, ident,videoId,params), "{body}", headers)
            response = conn.getresponse()
            videoUrl = response.read()
            # print(videoUrl)
            conn.close()
            return videoUrl
       
    except Exception as e:
        print("[Errno {0}] {1}".format(e.errno, e.strerror))


# In[34]:


# In[44]:

for i in range(len(videoThumbnailId)):

    videoId_2 =  videoId[i]
    videoPath = '{}/{}.mp4'.format(ident,videoId_2)
    # print(videoPath)
    videoUrl=str(getVideoUrl(ident, accessToken, accountType, videoId_2))
    
    if not os.path.isfile(videoPath):
        local_filename, headers= urllib.request.urlretrieve('%s'%videoUrl[3:-2], videoPath)
        #print('[Bye bye]')

print("/home/ubuntu/www/"+ident+"/videoData.json")

# In[ ]:


# In[30]:


# In[44]:
