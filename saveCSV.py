import nltk
from nltk.tag import pos_tag
from nltk.tokenize import word_tokenize
from nltk import FreqDist
import argparse
import csv

nltk.data.path.append('/home/ubuntu/www/nlt')
def most_common_word(args):


    path = "/home/ubuntu/www/"+args.ident
    file_path = path+"/"+args.output+".txt"
    print(file_path)
    f = open(file_path,"r")
    lines = f.read()
    tagged_list = pos_tag(word_tokenize(lines))
    allnoun=[word for word,pos in tagged_list if pos in ['NN','NNP']]

   
    #most frequency word
    fd_names = FreqDist(allnoun)
    mcw = fd_names.most_common(args.num)

    #convert tuple -> list
    mcw_list = []
    for row in mcw:
        row_list = list(row)
        row_list[1] = row_list[1]*10
        mcw_list.append(row_list)

    print(mcw_list)
    #save csv file
    csvfile = open(path+"/"+args.output+'.csv','w')
    csvwriter = csv.writer(csvfile)

    csvwriter.writerow(['text','frequency']);
    for row in mcw_list:
        csvwriter.writerow(row)
    csvfile.close()


def arg_parse():
    parser = argparse.ArgumentParser(description="Read file and extract most common word(noun)")
    parser.add_argument('--ident',dest='ident',help='input ident  id',default='1234',type=str)
    parser.add_argument("--num",dest='num',help='Enter the number of words you want to extract.',default=20,type=int)
    parser.add_argument("--output",dest='output',help='Enter path output file',default='/result.csv')

    return parser.parse_args()

if __name__ == '__main__':
    args = arg_parse()
    most_common_word(args)
    print("complete")


